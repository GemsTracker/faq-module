<?php

/**
 *
 * @package    GemsFaq
 * @subpackage Snippets
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2020, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace GemsFaq\Snippets;

/**
 *
 * @package    GemsFaq
 * @subpackage Snippets
 * @license    New BSD License
 * @since      Class available since version 1.9.1
 */
class PageEditSnippet extends \Gems_Snippets_ModelFormSnippetGeneric
{
    /**
     *
     * @var \MUtil_Acl
     */
    protected $acl;

    /**
     * @var \Zend_View
     */
    protected $view;
    
    /**
     * Adds elements from the model to the bridge that creates the form.
     *
     * Overrule this function to add different elements to the browse table, without
     * having to recode the core table building code.
     *
     * @param \MUtil_Model_Bridge_FormBridgeInterface $bridge
     * @param \MUtil_Model_ModelAbstract $model
     */
    protected function addFormElements(\MUtil_Model_Bridge_FormBridgeInterface $bridge, \MUtil_Model_ModelAbstract $model)
    {
        // \MUtil_Echo::track($this->acl->getChildRoles($this->formData['roles']));
        if (! (isset($this->formData['roles']) && is_array($this->formData['roles']))) {
            $this->formData['roles'] = [];
        }

        $children = $this->acl->getChildRoles($this->formData['roles']);
        $roles    = $model->get('roles', 'multiOptions');
        foreach ($children as $child) {
            $small = \MUtil_Html::create('small', $this->_('inherits from'), ' ');
            $seq = $small->seq(['glue' => $this->_(', ')]);
            foreach ($this->acl->getParentRoles($child) as $parent) {
                if (in_array($parent, $this->formData['roles']) && (! in_array($parent, $children))) {
                    $seq->em($parent);
                }
            }
            $roles[$child] .= $this->_(', ') . $small->render($this->view);
        }
        $this->formData['roles'] += $children;

        $model->set('roles', 'multiOptions', $roles,
              'disable', $children,
              'escape', false,
              'onchange', 'this.form.submit();'
        );
        parent::addFormElements($bridge, $model);
    }
}