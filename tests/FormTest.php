<?php

namespace PHPForms\Tests;

class FormTest extends \PHPUnit\Framework\TestCase
{
    public function testWeCanGetFields()
    {
        $fields = [
            'field_a' => [
                'label' => 'Field A',
                'type'  => 'text',
                'rules' => 'required|valid',
                'attribs' => [
                    'class' => 'a_class',
                    'id' => 'an_id'
                ]
            ],
            'field_b' => [
                'rules' => 'required|valid',
            ],
            'field_c' => [
                'attribs' => [
                    'class' => '<danger\'\">',
                ]
            ],
        ];

        $attribs = [
            'action' => './index.php',
            'class' => 'a_class',
            'id' => '<id>'
        ];

        $form = new \PHPForms\Form($fields, $attribs);

        $this->assertEquals($fields, $form->getFields());
        $this->assertEquals($fields['field_a'], $form->getField('field_a'));
    }

    public function testWeCanAddFields()
    {
        $form = new \PHPForms\Form();

        $options = [
            'label' => 'A field',
            'type'  => 'text',
            'rules' => 'rule1|rule2',
            'attribs' => [
                'class' => 'a_class',
                'at'    => 'value'
            ]
        ];

        $expected_fields = [
            'input' => $options
        ];

        $form->addField('input', $options);

        $this->assertEquals($expected_fields, $form->getFields());
    }

    public function testWeCanGetValidHtml()
    {
        $fields = [
            'field_a' => [
                'label' => 'Field A',
                'type'  => 'text',
                'rules' => 'required|valid',
                'attribs' => [
                    'class' => 'a_class',
                    'id' => 'an_id'
                ]
            ],
            'field_b' => [
                'rules' => 'required|valid',
            ],
            'field_c' => [
                'attribs' => [
                    'class' => '<danger\'">',
                ]
            ],
        ];

        $attribs = [
            'action' => './index.php',
            'class' => 'a_class',
            'id' => '<id>'
        ];

        $expected_html = '<form action="./index.php" class="a_class" id="&lt;id&gt;" method="POST">' . "\n"
                         .'<input name="field_a" type="text" placeholder="Field A" class="a_class" id="an_id">'
                         .'<input name="field_b" type="text">'
                         .'<input name="field_c" type="text" class="&lt;danger\'&quot;&gt;">' . "\n"
                         .'</form>' . "\n";


        $form = new \PHPForms\Form($fields, $attribs);

        $this->assertEquals($expected_html, $form->getHTML());

        $expected_html = '<form action="./index.php" class="a_class" id="&lt;id&gt;" method="POST">'
                         .'<input name="field_a" type="text" placeholder="Field A" class="a_class" id="an_id">'
                         .'<input name="field_b" type="text">'
                         .'<input name="field_c" type="text" class="&lt;danger\'&quot;&gt;">'
                         .'</form>' . "\n";

        $this->assertEquals($expected_html, $form->getHTML(false));
    }
}
