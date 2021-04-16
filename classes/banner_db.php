<?php

class Banner_db extends ObjectModel
{
    public $id;
    public $color;
    public $background_color;
    public $content;
    public $start_date;
    public $end_date;
    public $active = false;
    public $priority;

    public static $definition = [
        'table' => 'multi_banner',
        'primary' => 'id_multi_banner',
        'multilang' => false,
        'fields' => [
            'color' => [ 'type' => self::TYPE_STRING ],
            'background_color' => [ 'type' => self::TYPE_STRING ],
            'content' => [ 'type' => self::TYPE_STRING ],
            'start_date' => [ 'type' => self::TYPE_DATE ],
            'end_date' => ['type' => self::TYPE_DATE ],
            'active' => ['type' => self::TYPE_BOOL ],
            'priority' => ['type' => self::TYPE_INT],
        ]
    ];

    /**
     * Return the banner to display
     * 
     * Here we put the logic to select the right banner
     */
    public static function getBannerToDisplay()
    {
        $sql = 'SELECT *
                    FROM `' . _DB_PREFIX_ . self::$definition['table'] . '`
                    WHERE active = 1';

        return Db::getInstance()->getRow($sql);
    }
}