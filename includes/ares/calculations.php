<?php

namespace UniEngine\Engine\Includes\Ares\Calculations;

use UniEngine\Engine\Includes\Ares;

function calculateShipForce($params) {
    global $_Vars_CombatUpgrades, $_Vars_CombatData;

    $shipId = $params['shipId'];
    $userTechs = &$params['userTechs'];

    $weaponTechUpgradePercent = 0;

    if (!empty($_Vars_CombatUpgrades[$shipId])) {
        foreach ($_Vars_CombatUpgrades[$shipId] as $upgradeTechId => $upgradeTechLevelRequirement) {
            $upgradeTechUserLevel = $userTechs[$upgradeTechId];

            if ($upgradeTechUserLevel <= $upgradeTechLevelRequirement) {
                continue;
            }

            $weaponTechUpgradePercent += ($upgradeTechUserLevel - $upgradeTechLevelRequirement) * 0.05;
        }
    }

    return floor(
        $_Vars_CombatData[$shipId]['attack'] *
        ($userTechs[109] + $weaponTechUpgradePercent) *
        $userTechs['TotalForceFactor']
    );
}

function calculateShipShield($params) {
    global $_Vars_CombatData;

    $shipId = $params['shipId'];
    $userTechs = &$params['userTechs'];

    return floor(
        $_Vars_CombatData[$shipId]['shield'] *
        $userTechs[110] *
        $userTechs['TotalShieldFactor']
    );
}

function calculateShipHull($params) {
    global $_Vars_Prices;
    static $baseHullValuesCache = [];

    $shipId = $params['shipId'];
    $userTechs = &$params['userTechs'];

    if (empty($baseHullValuesCache[$shipId])) {
        $baseHullValuesCache[$shipId] = (
            ($_Vars_Prices[$shipId]['metal'] + $_Vars_Prices[$shipId]['crystal']) /
            10
        );
    }

    return floor(
        $baseHullValuesCache[$shipId] *
        $userTechs[111]
    );
}

function calculateShieldsTakeDownStats($params) {
    $shotForce = $params['shotForce'];
    $targetFullKey = $params['targetFullKey'];
    $targetShipShield = $params['targetShipShield'];
    $targetShipCount = $params['targetShipCount'];
    $roundShieldStateCache = $params['roundShieldStateCacheByTargetKey'];

    $isShotBypassingShield = Ares\Evaluators\isShotBypassingShield([
        'shotForce' => $shotForce,
        'targetShipShield' => $targetShipShield,
    ]);

    if ($isShotBypassingShield) {
        return [
            'forceNeeded' => 0,
            'isShotBypassingShield' => true,
        ];
    }

    $isTargetsShieldDamaged = (
        isset($roundShieldStateCache[$targetFullKey]['left']) &&
        $roundShieldStateCache[$targetFullKey]['left'] === true
    );

    $forceNeeded = (
        $isTargetsShieldDamaged ?
            $roundShieldStateCache[$targetFullKey]['shield'] :
            ($targetShipShield * $targetShipCount)
    );

    return [
        'forceNeeded' => $forceNeeded,
        'isShotBypassingShield' => false,
    ];
}

?>
