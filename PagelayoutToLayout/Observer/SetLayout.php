<?php
/**
 * @author makin
 * @date 2024
 * 
 * set current page layout to certain page_layout, 
 * page_layout is selectable in admin page without sku/id hardcoding,
 * this helps using "Custom Layout Update" in admin can be softcoded instaead of hardcoded with certain known sku/categoryid
 * this also helps building layout (page_layout/*.xml) that can add css and js in header or other blocks
 * 
 * 
 * how to
 * - add page_layout in      app/design/frontend/Vendor/Name/Magento_Theme/layouts.xml
 * - add page_layout file to app/design/frontend/Vendor/Name/Magento_Theme/page_layout/a.xml
 * - add layout file      to app/design/frontend/Vendor/Name/Magento_Theme/layout/b.xml
 * this will make for every page that use page_layout a, it will also apply layout b
 * this is useful to add item to header, like css/js only for specific page_layout
 * 
 */
namespace Makin\PagelayoutToLayout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\LayoutInterface;
use Magento\Framework\View\Page\Config as PageConfig; 
use Magento\Framework\Component\ComponentRegistrar;

class SetLayout implements ObserverInterface
{
    /**
     * @var PageConfig
     */
    private $pageConfig;
    private $setList;
    private $componentRegistrar;

    public function __construct(
        PageConfig $pageConfig
        ,ComponentRegistrar $componentRegistrar
    )
    {
        $this->pageConfig = $pageConfig;
        $this->componentRegistrar = $componentRegistrar;

        $this->setList = $this->loadSetList();
    }

    // update this list as needed
    private function loadSetList()
    {
        $moduleDir = $this->componentRegistrar->getPath(
            ComponentRegistrar::MODULE,
            'Makin_PagelayoutToLayout'
        );
        $filePath = $moduleDir . '/etc/layout_mapping.json';
        $jsonContent = file_get_contents($filePath);
        $setList = json_decode($jsonContent, true);
        return $setList;
    }

    public function execute(Observer $observer)
    {
        /*
        page_configurations.xsd (layout/*.xml) can be fetched with
            $observer->getEvent()->getLayout()->getUpdate()->getHandles();
        and page_layout.xsd (page_layout/*.xml) can be fetched with injecting
            Magento\Framework\View\Page\Config -> getPageLayout()
        
        magento2 confused itself by mixing page_configuration.xsd with Page\Config of page_layout.xsd on top of having layout and page_layout, so it might be changed in the future
        */
        /** @var LayoutInterface $layout */
        $layout = $observer->getEvent()->getLayout();
        // Get the page layout
        $pageLayout = $this->pageConfig->getPageLayout();
        
        // check for page_layout and apply layout as needed
        foreach ($this->setList as $key => $value) {
            if ($pageLayout==$key) { //check if current page_layout is equal to the key
                $layout->getUpdate()->addHandle($value); //set page layout handle to the value
            }
        }

    }
}
