<?php

/**
 * Class Image
 */
class Image extends Model
{
    /**
     * @var array
     */
    protected $endMass = [];

    /**
     * @var array
     */
    protected $arrayAllSrc = [];

    /**
     * @var array
     */
    protected $arrayForModel = [];

    /**
     * @var array
     */
    public $endMassForRecord = [];

    /**
     * Image constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->arrayAllSrc = $this->getAllSrc();
        $this->arrayForModel = $this->getAllPosition();
        $this->MassiveForFlexSlider($this->arrayForModel);
        $this->arrayEndMassForRecord();
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function MassiveForFlexSlider(array $data)
    {
        $mass = [];
        foreach ($data as $key => $value) {
            $mass[$data[$key]['id_banner']] = $data[$key]['number_position'];
        }

        $count = count($mass);

        for ($i = 0; $i <= $count; $i++) {
            if (count($mass) == 0) {
                return false;
            }
            $min = min($mass);

            foreach ($mass as $key => $value) {
                if ($value == $min && $value != $mass[$key + 1]) {
                    $this->endMass[$key] = $value;
                    unset($mass[$key]);
                    @$min = min($mass);
                }
            }
        }
    }

    /**
     * Здесь происходит генерация массива для вывода во слайдере.
     */
    protected function arrayEndMassForRecord()
    {
        $mass = $this->arrayAllSrc;
        foreach ($this->endMass as $key => $value) {
            foreach ($mass as $keys => $values) {
                if ((int)$mass[$keys]['id'] == $key) {
                    $this->endMassForRecord[] =
                        [
                            'id' => $mass[$keys]['id'],
                            'name' => $mass[$keys]['name'],
                            'file_src' => $mass[$keys]['file_src'],
                            'url' => $mass[$keys]['url'],
                            'status' => $mass[$keys]['status'],
                        ];
                }
            }
        }
    }

    /**
     * @return array
     */
    public function arrayAllSrc()
    {
        return $this->arrayAllSrc;
    }

    /**
     * @return array
     */
    public function getEndMass()
    {
        return $this->endMass;
    }

    /**
     * @return array
     */
    public function arrayForModel()
    {
        return $this->arrayForModel;
    }
}
