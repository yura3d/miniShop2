<?php

abstract class MODxProcessorTestCase extends MODxTestCase {

    public $processor;

    protected function getResponse($data) {
        /** @var modProcessorResponse $response */
        $response = $this->modx->runProcessor($this->processor, $data, $this->path);
        $response = $response->getResponse();
        if (!is_array($response)) {
            $response = $this->modx->fromJSON($response);
        }

        echo "\r\n".$this->processor ." response:\r\n";
       // var_dump($response);
        return $response;
    }

    protected function createTestCategory($pagetitle, $properties = array()) {
        /** @var msCategory $category */
        $category = $this->modx->newObject('msCategory');
        $category->set('pagetitle', $pagetitle);
        if (count($properties) > 0) {
            $category->fromArray($properties);
        }
        $category->save();

        return $category;
    }

    protected function createTestProduct($pagetitle, $category, $properties = array()) {
        /** @var msProduct $product */
        $product = $this->modx->newObject('msProduct');
        $product->set('pagetitle', $pagetitle);
        $product->set('parent', $category);
        if (count($properties) > 0) {
            $product->fromArray($properties);
        }
        $product->save();

        return $product;
    }

    protected function createTestOption($name, $properties = array()) {
        /** @var msFeature $feature */
        $option = $this->modx->newObject('msOption');
        $option->set('key', $name);
        $option->fromArray($properties);
        $option->save();

        return $option;
    }

    protected function createTestCategoryOption($cat_id, $option_id, $properties = array()) {
        /** @var msCategoryFeature $catFeature */
        $catOption = $this->modx->newObject('msCategoryOption');
        $catOption->set('category_id', $cat_id);
        $catOption->set('option_id', $option_id);
        $catOption->fromArray($properties);
        $catOption->save();

        return $catOption;
    }

    protected function createTestProductOption($product_id, $option_id, $properties = array()) {
        /** @var msProductFeature $prodFeature */
        $prodOption = $this->modx->newObject('msProductOption');
        $prodOption->set('product_id', $product_id);
        $prodOption->set('key', $option_id);
        $prodOption->fromArray($properties);
        $prodOption->save();
        return $prodOption;
    }

    public function tearDown() {
        parent::tearDown();

        /* Remove test categories */
        $category = $this->modx->getCollection('modResource',array('pagetitle:LIKE' => '%UnitTest%'));
        /** @var msCategory $cat */
        foreach ($category as $cat) {
            $cat->remove();
        }
        $this->modx->query("ALTER TABLE ".$this->modx->getTableName('msCategory')." AUTO_INCREMENT = 0;");

        /* Remove test features */
        $objs = $this->modx->getCollection('msOption',array('key:LIKE' => '%UnitTest%'));
        /** @var xPDOObject $obj */
        foreach ($objs as $obj) {
            $obj->remove();
        }
        $this->modx->query("ALTER TABLE ".$this->modx->getTableName('msOption')." AUTO_INCREMENT = 0;");

        $this->modx->query("ALTER TABLE ".$this->modx->getTableName('msProductData')." AUTO_INCREMENT = 0;");
        $this->modx->query("ALTER TABLE ".$this->modx->getTableName('msCategoryOption')." AUTO_INCREMENT = 0;");
        $this->modx->query("ALTER TABLE ".$this->modx->getTableName('msProductOption')." AUTO_INCREMENT = 0;");

    }
}
