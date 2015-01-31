<?php
    namespace Task\Model;
    class User
    {
        public $id;
        public $name;
        function exchangeArray($data)
        {
            $this->name = (isset($data['name'])) ?
                $data['name'] : null;


        }
    }
