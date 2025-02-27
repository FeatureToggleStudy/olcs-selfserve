<?php

namespace Permits\Controller\Config\FeatureToggle;

use Common\FeatureToggle;

/**
 * Holds feature toggle configs that are used regularly
 */
class FeatureToggleConfig
{
    const SELFSERVE_PERMITS_ENABLED =  [
        FeatureToggle::SELFSERVE_PERMITS
    ];

    const SELFSERVE_SURRENDER_ENABLED = [
        FeatureToggle::SELFSERVE_SURRENDER
    ];
}
