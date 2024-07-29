<?php

declare(strict_types=1);

namespace Atma\PokemonIntegration\Model\Config\Backend;

use Exception;
use Magento\Cron\Model\Config\Source\Frequency;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value as ConfigValue;
use Magento\Framework\App\Config\ValueFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

class CacheWarmerCron extends ConfigValue
{
    const CRON_STRING_PATH = 'crontab/default/jobs/pokemon_integration_cache_warmer/schedule/cron_expr';

    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        private readonly ValueFactory $configValueFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * @throws LocalizedException
     */
    public function afterSave()
    {
        $enabled = $this->getData('groups/cache/fields/warmer_enabled/value');
        $time = $this->getData('groups/cache/fields/warmer_time/value');
        $frequency = $this->getData('groups/cache/fields/warmer_frequency/value');
        $frequencyWeekly = Frequency::CRON_WEEKLY;
        $frequencyMonthly = Frequency::CRON_MONTHLY;

        if ($enabled) {
            $cronExprArray = [
                (int) $time[1],                                   # Minute
                (int) $time[0],                                   # Hour
                $frequency == $frequencyMonthly ? '1' : '*',      # Day of the Month
                '*',                                              # Month of the Year
                $frequency == $frequencyWeekly ? '1' : '*',       # Day of the Week
            ];
            $cronExprString = join(' ', $cronExprArray);
        } else {
            $cronExprString = '';
        }

        try {
            $configValue = $this->configValueFactory->create();
            $configValue->load(self::CRON_STRING_PATH, 'path');
            $configValue->setValue($cronExprString)->setPath(self::CRON_STRING_PATH)->save();
        } catch (Exception $e) {
            throw new LocalizedException(__('We can\'t save the Cron expression.'));
        }

        return parent::afterSave();
    }
}
