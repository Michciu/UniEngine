<?php

namespace UniEngine\Engine\Modules\FlightControl\Utils\Validators;

/**
 * @param array $props
 * @param Record<shipId: string, shipCount: string> $props['fleet']
 * @param ref $props['planet']
 * @param boolean | null $props['isFromDirectUserInput']
 *  - Allows ships count to be prettified
 *  - Allows ships count to be 0
 */
function validateFleetArray ($props) {
    global $_Vars_ElementCategories, $_Vars_Prices;

    $isValid = function () {
        return [
            'isValid' => true,
        ];
    };
    $isInvalid = function ($errors) {
        return [
            'isValid' => false,
            'errors' => $errors,
        ];
    };

    $fleet = $props['fleet'];
    $planet = &$props['planet'];
    $isFromDirectUserInput = (
        isset($props['isFromDirectUserInput']) ?
            $props['isFromDirectUserInput'] :
            false
    );

    foreach ($fleet as $inputShipId => $inputShipCount) {
        $shipId = intval($inputShipId);

        if (!in_array($shipId, $_Vars_ElementCategories['fleet'])) {
            return $isInvalid([
                [ 'errorCode' => 'INVALID_SHIP_ID', ],
            ]);
        }

        if (empty($_Vars_Prices[$shipId]['engine'])) {
            return $isInvalid([
                [ 'errorCode' => 'SHIP_WITH_NO_ENGINE', ],
            ]);
        }

        // TODO: create generic "de-prettify" function for these values
        $tempShipCount = (
            $isFromDirectUserInput ?
                str_replace('.', '', $inputShipCount) :
                $inputShipCount
        );
        $shipCount = floor($tempShipCount);

        if ($shipCount < 0) {
            return $isInvalid([
                [ 'errorCode' => 'INVALID_SHIP_COUNT', ],
            ]);
        }
        if (!$isFromDirectUserInput && $shipCount == 0) {
            return $isInvalid([
                [ 'errorCode' => 'INVALID_SHIP_COUNT', ],
            ]);
        }

        $planetElementKey = _getElementPlanetKey($shipId);

        if ($planet[$planetElementKey] < $shipCount) {
            return $isInvalid([
                [ 'errorCode' => 'SHIP_COUNT_EXCEEDS_AVAILABLE', ],
            ]);
        }

        continue;
    }

    return $isValid();
}

?>
