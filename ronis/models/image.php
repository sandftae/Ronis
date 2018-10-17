<?php

/*
 * Этот класс отвечает за лоогику вывода изображений в слайдере.
 */

class Image extends Model{


    protected $endMass = [];
    protected $arrayAllSrc = [];
    protected $arrayForModel = [];
    public $endMassForRecord = [];


    public function __construct(){
        parent::__construct();
        $this -> arrayAllSrc = $this -> getAllSrc();
        $this -> arrayForModel = $this -> getAllPosition();
        $this -> MassiveForFlexSlider($this -> arrayForModel);
        $this -> arrayEndMassForRecord();
    }

    protected function MassiveForFlexSlider (array $data)
    {
        $mass = [];
        foreach($data as $key => $value){
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

    /*
     * Здесь происходит генерация массива для вывода во слайдере. Метод проверяет
     * заданную последовательность в методе MassiveForFlexSlider. После чего согласно
     * этой же последовательности генерит новый массив, в который просто по порядку вносчит
     * данные из массива $this -> arrayAllSrc в соответсвии с порядковым
     *  номером по id. После результат ввполнение метода передается во вью
     */
    protected function arrayEndMassForRecord(){
        $mass = $this -> arrayAllSrc;
        foreach($this -> endMass as $key => $value){
            foreach( $mass as $keys => $values){
                if((int)$mass[$keys]['id'] == $key){
                    $this -> endMassForRecord[] =
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


    public function arrayAllSrc(){
        return $this -> arrayAllSrc;
    }
    public function getEndMass(){
        return $this -> endMass;
    }
    public function arrayForModel(){
        return $this -> arrayForModel;
    }
}
