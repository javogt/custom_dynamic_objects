<?php
use Illuminate\Database\Schema\Blueprint;

class CustomDynamicObjects
{
    
    protected $wpConnector;
    protected $jsons;
    protected $capsule;
    
    protected $options = ['tablePrefix' => 'customDynamicObjects_'];
    
    /**
     * @description Set options
     */
    public function __construct($connector, $jsons, $capsule) {
        $this->wpConnector = $connector;
        $this->jsons = $jsons;
        $this->capsule = $capsule;
    }
    
    public function addConnection($wpdb) {
        $this->capsule->addConnection(['driver' => 'mysql', 'host' => $wpdb->dbhost, 'database' => $wpdb->dbname, 'username' => $wpdb->dbuser, 'password' => $wpdb->dbpassword, 'charset' => 'utf8', 'collation' => 'utf8_general_ci', 'prefix' => $wpdb->prefix]);
        $this->capsule->setAsGlobal();
    }
    
    /**
     * @description Creates markup for backend meta box
     * @return {String} html for meta box
     */
    public function customDynamicObjectsMetaBox() {
        $objectTypes = $this->jsons->getObjectTypes();
        if (!empty($objectTypes)) {
            $html = '<ul>';
            foreach ($objectTypes as $objectType) {
                $html.= '<li>';
                $html.= $this->jsons->getObjectLabel($objectType);
                $html.= '</li>';
            }
            $html.= '</ul>';
            echo $html;
        }
    }
    
    /**
     * @description Adds meta box to wp backend
     */
    public function addingObjectTypeMetaBox() {
        $this->wpConnector->add_meta_box('custom_dynamic_objects', 'Object Type', array(
            $this,
            'customDynamicObjectsMetaBox'
        ) , 'post', 'side', 'high', null);
    }
    
    /**
     * @description Creating part of backend in wp posts backend
     */
    public function createBackend() {
        $this->wpConnector->add_action('add_meta_boxes', array(
            $this,
            'addingObjectTypeMetaBox'
        ));
    }
    
    /**
     * @description Creates table name to given objectType
     * @param  {Array} $objectType
     * @return {String}
     */
    private function getTableNameByObjectType($objectType) {
        $fileName = $this->jsons->getFileNameFromPath($objectType['file']);
        return $this->options['tablePrefix'] . $fileName;
    }
    
    /**
     * @description Creates callback funton for eloquent/blueprint migration from given object type
     * @param  {Array} $objectType
     * @return {Closure}
     */
    private function getMigrateCallbackFuntionByObjectType($objectType) {
        $properties = empty($objectType['properties']) ? [] : $objectType['properties'];
        return function (Blueprint $table) use ($properties) {
            
            // Adding an incremental id to every table
            $table->increments('id');
            foreach ($properties as $column) {
                $table->$column['function']($column['param']);
            }
        };
    }
    
    /**
     * @description Creates table for given objectType
     * @param  {Array} $objectType
     */
    private function createTableByObjectType($objectType) {
        $tableName = $this->getTableNameByObjectType($objectType);
        $callBack = $this->getMigrateCallbackFuntionByObjectType($objectType);
        $this->capsule->schema()->create($tableName, $callBack);
    }
    
    /**
     * @description Creates tables for all objectTypes
     */
    public function migrate() {
        $objectTypes = $this->jsons->getObjectTypes();
        
        //$this->capsule->schema()->dropIfExists('testyfy');
        foreach ($objectTypes as $objectType) {
            $this->createTableByObjectType($objectType);
        }
    }
}
?>