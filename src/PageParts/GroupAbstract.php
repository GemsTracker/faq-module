<?php

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2020, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace GemsFaq\PageParts;

use Zalt\Loader\Translate\TranslateableAbstract;

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts
 * @license    New BSD License
 * @since      Class available since version 1.9.1
 */
abstract class GroupAbstract extends TranslateableAbstract implements GroupPartInterface
{
    /**
     * @var Source data 
     */
    protected $data;

    /**
     * @var array 
     */
    protected $exampleData = [
        'gfg_group_name' => 'Example name',
        'gfg_display_method' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur efficitur finibus mauris tempor porttitor. Ut mattis neque sit amet orci placerat ornare. Suspendisse potenti. Ut at libero malesuada, facilisis ipsum at, blandit nisi. Sed elementum tellus id justo imperdiet, vel pharetra mi ultrices. Pellentesque non nunc varius, aliquam lorem sed, tincidunt velit. Nulla non enim non nulla sollicitudin convallis. Curabitur vestibulum ultricies tellus. Fusce faucibus efficitur lacus, vel efficitur ipsum consectetur sed.

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sit amet nisi dapibus, lobortis nulla in, dignissim arcu. Pellentesque vel bibendum mauris. Suspendisse cursus, ipsum nec egestas elementum, nisi odio gravida ex, nec fringilla nulla leo eu augue. Fusce vel convallis diam. In lacinia massa sit amet ante consequat venenatis. Aenean semper elit vel pulvinar vulputate. Morbi ac turpis condimentum, gravida augue non, feugiat tellus. Nunc eu est non nibh tincidunt mattis vel sed dolor. In hac habitasse platea dictumst. In metus erat, fermentum ac vulputate ac, facilisis eu ligula.

Fusce ultricies nibh eu leo consectetur accumsan. Ut lobortis volutpat sapien non tincidunt. Ut sit amet felis vel lorem malesuada finibus ut eu ipsum. Proin iaculis, libero vehicula varius auctor, magna ligula faucibus nulla, id mattis odio lacus vitae augue. Nulla facilisi. Donec luctus suscipit erat et bibendum. Nunc tincidunt justo quis quam fermentum, vitae ornare massa efficitur. Pellentesque eleifend vitae erat vitae placerat. Maecenas interdum libero vestibulum mollis porta. Maecenas porta, turpis vitae malesuada convallis, dolor mi vehicula nisi, at scelerisque odio turpis nec est. Fusce eleifend elit ut dui rutrum aliquet.',
        'gfg_active' => 1,
        ];

    /**
     * @var \GemsFaq\FaqPageParts
     */
    protected $faqParts;

    /**
     * @var 
     */
    protected $groupItems;
    
    /**
     * @inheritDoc
     */
    public function exchangeArray(array $data)
    {
        $this->data = $data;
        
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getExample()
    {
        $oldData = $this->data;
        $oldGroups = $this->groupItems;
        
        $example = new ExampleItemPart();
        $this->data = $this->exampleData;
        $this->groupItems = [$example, $example, $example];
        
        $output = $this->getHtmlOutput();

        $this->data = $oldData;
        $this->groupItems = $oldGroups;
        
        return $output;
    }

    /**
     * @return array itemId => ItemPartInterface
     */
    protected function getGroupItems()
    {
        if (! $this->groupItems) {
            $this->groupItems = $this->faqParts->getGroupItems($this->data['gfg_id']);
        }
        return $this->groupItems;
    }
    
    /**
     * Helper function for snippets returning a sequence of Html items.
     *
     * @return \MUtil_Html_Sequence
     */
    protected function getHtmlSequence()
    {
        return new \MUtil_Html_Sequence();
    }
}