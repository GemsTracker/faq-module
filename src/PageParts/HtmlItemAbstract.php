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

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts
 * @license    New BSD License
 * @since      Class available since version 1.9.1
 */
abstract class HtmlItemAbstract extends ItemAbstract
{
    /**
     * @var array
     */
    protected $exampleData = [
        'gfi_title' => 'Example question',
        'gfi_body' => '<b>Lorem ipsum dolor sit</b> amet, <i>consectetur adipiscing</i> elit. Curabitur efficitur finibus mauris tempor porttitor. Ut mattis neque sit amet orci placerat ornare. Suspendisse potenti. Ut at libero malesuada, facilisis ipsum at, blandit nisi. Sed elementum tellus id justo imperdiet, vel pharetra mi ultrices. Pellentesque non nunc varius, aliquam lorem sed, tincidunt velit. Nulla non enim non nulla sollicitudin convallis. Curabitur vestibulum ultricies tellus. Fusce faucibus efficitur lacus, vel efficitur ipsum consectetur sed.
<br/><br/>
Lorem ipsum dolor sit amet, consectetur adipiscing elit. <b>Maecenas sit amet nisi dapibus</b>, lobortis nulla in, dignissim arcu. Pellentesque vel bibendum mauris. Suspendisse cursus, ipsum nec egestas elementum, nisi odio gravida ex, nec fringilla nulla leo eu augue. Fusce vel convallis diam. In lacinia massa sit amet ante consequat venenatis. Aenean semper elit vel pulvinar vulputate. Morbi ac turpis condimentum, gravida augue non, feugiat tellus. Nunc eu est non nibh tincidunt mattis vel sed dolor. In hac habitasse platea dictumst. In metus erat, fermentum ac vulputate ac, facilisis eu ligula.
<br/><br/>
Fusce ultricies nibh eu leo consectetur accumsan. Ut lobortis volutpat sapien non tincidunt. Ut sit amet felis vel lorem malesuada finibus ut eu ipsum. Proin iaculis, libero vehicula varius auctor, magna ligula faucibus nulla, id mattis odio lacus vitae augue. Nulla facilisi. Donec luctus suscipit erat et bibendum. Nunc tincidunt justo quis quam fermentum, vitae ornare massa efficitur. Pellentesque eleifend vitae erat vitae placerat. Maecenas interdum libero vestibulum mollis porta. Maecenas porta, turpis vitae malesuada convallis, dolor mi vehicula nisi, at scelerisque odio turpis nec est. Fusce eleifend elit ut dui rutrum aliquet.',
        'gfg_active' => 1,
    ];

    /**
     * @var \GemsFaq\Util\FaqUtil
     */
    public $faqUtil;

    /**
     * @var \Zend_View
     */
    protected $view;

    /**
     * @var bool Check whether the view has been set
     */
    private static $viewIsSet = false;

    /**
     * Called after the check that all required registry values
     * have been set correctly has run.
     *
     * This function is no needed if the classes are setup correctly
     *
     * @return void
     */
    public function afterRegistry()
    {
        parent::afterRegistry();

        if (self::$viewIsSet) {
            return;
        }
        
        $config = array(
            // 'default' => ['name' => 'Button', 'element' => 'a', 'style' => 'display: background-color: red; block; padding: 1em;'],
            'colorButton_colors' => ['00923E','F8C100','28166F'],
            'disableObjectResizing' => true,
            // 'fontSize_sizes' => "30/30%;50/50%;100/100%;120/120%;150/150%;200/200%;300/300%",
            'extraPlugins' => 'availablefields',
            'removePlugins' => 'bbcode',
            'justifyClasses' => ['text-left', 'text-center', 'text-right', 'text-align'],
            'toolbar' => array(
                array('Source', 'Maximize', '-', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo','Redo'),
                // array('Find','Replace','-','SelectAll'),
                array('Link', 'Unlink', 'Image', 'Smiley', 'SpecialChar'),
                '/',
                array('Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat', 'TextColor'), 
                // array('Outdent', 'Indent'),
                array('NumberedList','BulletedList','-','Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'),
                array('availablefields'),
                array('Styles', 'Format'), // , 'Font', 'FontSize', '-', 'TextColor', 'BGColor'),
            )
        );
        $config['availablefields'] = $this->getFieldList();
        $config['availablefieldsLabel'] = $this->_('Add files');
        $config['availablefieldsDisplay'] = 'value';
        
        $this->view->inlineScript()->prependScript("
                CKEditorConfig = ".\Zend_Json::encode($config).";
                ");
        
        self::$viewIsSet = true;
    }

    /**
     * @inheritDoc
     */
    public function getBodySettings()
    {
        return [
            'label'          => $this->_('Answer') . "\n",
            'autoInsertNoTagsValidator' => false,
            'cols'           => 60,
            'decorators'     => ['CKEditor'],
            'elementClass'   => 'Textarea',
            'formatFunction' => [$this, 'toHtml'],
            'required'       => true,
            'rows'           => 8,
            ];
    }
    
    public function getFieldList()
    {
        $docUrl = $this->faqUtil->getDocumentUrl();
        $output = [];

        // \MUtil_Echo::track($docUrl);
        
        $docModel = $this->faqUtil->getDocumentModel(true, [\MUtil_File::$documentExtensions, \MUtil_File::$textExtensions]);
        $docs     = $docModel->load();
        if ($docs) {
            $label = $this->_('Download links');
            $output[$label] = '<strong><em>' . $label . '</em></strong>';
            // \MUtil_Echo::track($docs);
            foreach ($docs as $doc) {
                $link = \MUtil_Html_AElement::a($docUrl . '/' . str_replace('\\', '/', $doc['relpath']), $doc['filename']);
                $link->class = $this->faqUtil->getExtensionClass($doc['extension']);
                $output[$link->render($this->view)] = $doc['relpath'];
            }
        }

        \MUtil_Html_ImgElement::addImageDir($docUrl);
        $imageModel = $this->faqUtil->getDocumentModel(true, \MUtil_File::$imageExtensions);
        $images     = $imageModel->load();

        if ($images) {
            $label = $this->_('Images');
            $output[$label] = '<strong><em>' . $label . '</em></strong>';
            // \MUtil_Echo::track($images);
            foreach ($images as $image) {
                $img = \MUtil_Html_ImgElement::imgFile($image['relpath']);
                $output[$img->render($this->view)] = $image['relpath'];
            }
        }
        // \MUtil_Echo::track($output);
        
        return $output;
    }
    
    /**
     * Optional additional instructions
     *
     * @return \MUtil_Html_HtmlInterface Something that can be rendered
     */
    public function getInstructions()
    {
        $seq = $this->getHtmlSequence();
        $seq->pInfo()->bbcode($this->_('Use the [code]Source[/code] button to view the underlying codes.'));
        
        return $seq;
    }
    
    /**
     * Display a template body
     *
     * @param string $code
     * @return \MUtil_Html_HtmlElement
     */
    public function toHtml($code)
    {
        if (empty($code)) {
            $em = \MUtil_Html::create('em');
            $em->raw($this->_('&laquo;empty&raquo;'));

            return $em;
        }

        $div = \MUtil_Html::create('div', array('class' => 'mailpreview'));
        $div->raw($code);

        return $div;
    }
}