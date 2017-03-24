<?php

namespace LukePOLO\FakeRealAddresses;

use Geocoder\Model\Address;
use Geocoder\Exception\NoResult;
use Geocoder\Provider\GoogleMaps;
use Ivory\HttpAdapter\CurlHttpAdapter;

class Generator
{
    public $geocoder;

    public $topBoundary = '49.04';
    public $leftBoundary = '-124.26';
    public $rightBoundary = '-66.97';
    public $bottomBoundary = '26.65';

    /**
     * FakeRealAddresses constructor.
     * @param null $apiKey
     */
    public function __construct($apiKey = null)
    {
        $this->geocoder = new GoogleMaps(new CurlHttpAdapter(), null, null, true, $apiKey);
    }

    /**
     * @param int $numberOfAddresses
     * @return array|mixed
     */
    public function make($numberOfAddresses = 1)
    {
        $addresses = [];

        while(count($addresses) < $numberOfAddresses) {
            $addresses[] = $this->getLocation(
                $this->makeLng($this->bottomBoundary, $this->topBoundary),
                $this->makeLat($this->leftBoundary, $this->rightBoundary)
            );
        }

        if(count($addresses) == 1) {
            return $addresses[0];
        }

        return $addresses;
    }

    /**
     * @param $latitude
     * @param $longitude
     * @return array|mixed
     */
    public function getLocation($latitude, $longitude)
    {
        try {
            /** @var Address $address */
            $address = $this->geocoder->reverse($latitude, $longitude)->first();

            if(empty($address->getStreetNumber())) {
                return $this->make();
            }

            $streetNumber = explode('-', $address->getStreetNumber());

            if(count($streetNumber) == 2) {
                $streetNumber = rand($streetNumber[0], $streetNumber[1]);
            } else {
                $streetNumber = $streetNumber[0];
            }

            return [
                'street' => $streetNumber.' '.$address->getStreetName(),
                'city' => $address->getLocality(),
                'province' => $address->getAdminLevels()->first()->getName(),
                'country' => $address->getCountryCode(),
                'postal_code' => $address->getPostalCode(),
                'latitude' => $address->getLatitude(),
                'longitude' => $address->getLongitude()
            ];

        } catch(NoResult $e) {

            return $this->make();
        }
    }

    /**
     * @param int $min
     * @param int $max
     * @return float
     */
    private function makeLat($min = -90, $max = 90)
    {
        return $this->randomFloat(6, $min, $max);

    }

    /**
     * @param int $min
     * @param int $max
     * @return float
     */
    private function makeLng($min = -180, $max = 180)
    {
        return $this->randomFloat(6, $min, $max);
    }

    /**
     * Return a random float number
     *
     * @param int       $nbMaxDecimals
     * @param int|float $min
     * @param int|float $max
     * @example 48.8932
     *
     * @return float
     */
    private function randomFloat($nbMaxDecimals = null, $min = 0, $max = null)
    {
        if (null === $nbMaxDecimals) {
            $nbMaxDecimals = static::randomDigit();
        }
        if (null === $max) {
            $max = static::randomNumber();
            if ($min > $max) {
                $max = $min;
            }
        }
        if ($min > $max) {
            $tmp = $min;
            $min = $max;
            $max = $tmp;
        }
        return round($min + mt_rand() / mt_getrandmax() * ($max - $min), $nbMaxDecimals);
    }
}