<?php

namespace Netgen\Bundle\EzFormsBundle\Form\FieldTypeHandler;

use eZ\Publish\SPI\FieldType\Value;
use Netgen\Bundle\EzFormsBundle\Form\FieldTypeHandler;
use Symfony\Component\Form\FormBuilderInterface;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\Core\FieldType\Float as FloatValue;
use Symfony\Component\Validator\Constraints as Assert;

class Float extends FieldTypeHandler
{
    /**
     * {@inheritdoc}
     */
    protected function buildFieldForm(
        FormBuilderInterface $formBuilder,
        FieldDefinition $fieldDefinition,
        $languageCode,
        Content $content = null
    )
    {
        $options = $this->getDefaultFieldOptions( $fieldDefinition, $languageCode, $content );

        if ( !empty( $fieldDefinition->getValidatorConfiguration()['FloatValueValidator'] ) )
        {
            $min = $fieldDefinition->getValidatorConfiguration()['FloatValueValidator']['minFloatValue'];
            $max = $fieldDefinition->getValidatorConfiguration()['FloatValueValidator']['maxFloatValue'];
            $options['constraints'][] = new Assert\Range(
                array(
                    'min' => $min,
                    'max' => $max,
                )
            );
        }

        if ( !empty( $fieldDefinition->defaultValue ) )
        {
            $options['data'] = (float)$fieldDefinition->defaultValue->value;
        }

        $formBuilder->add( $fieldDefinition->identifier, 'number', $options );
    }

    /**
     * {@inheritDoc}
     *
     * @return float
     */
    public function convertFieldValueToForm( Value $value )
    {
        return $value->value;
    }

    /**
     * {@inheritDoc}
     *
     * @return FloatValue\Value
     */
    public function convertFieldValueFromForm( $data )
    {
        if ( !is_numeric( $data ) )
        {
            $data = null;
        }
        return new FloatValue\Value( $data );
    }
}