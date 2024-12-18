<?php

declare(strict_types=1);

/**
 * @package    Grav\Common\Flex
 *
 * @copyright  Copyright (c) 2024 Benjamin Currey
 * @license    MIT License; see LICENSE file for details.
 */

namespace Grav\Plugin\FormBuilder\Flex\Types\FormBuilder;

use Grav\Plugin\FormBuilder\Utils;
use Grav\Common\Flex\FlexObject;
use Grav\Common\File\CompiledMarkdownFile;
use Grav\Common\Grav;
use Grav\Common\Data\Data;
// use Grav\Common\User\Interfaces\UserInterface;
// use function is_array;

/**
 * Class FormBuilderObject
 * @package Grav\Common\Flex\Generic
 *
 * @extends FlexObject<string,GenericObject>
*/
class FormBuilderObject extends FlexObject
{

    /**
     * {@inheritdoc}
     * @see FlexObjectInterface::save()
     */
    public function save()
    {
        $grav = Grav::instance();
        $form_name = '';

        // TODO: Clean up this mess
        // Get the current data as an array
        // $data = (array)$this->getNestedProperty('header.form.fields');

        // $data = $this->getNestedProperty( 'header.form.fields' );
        //
        // if (isset($data['header']['form']['fields']) && is_array($data['header']['form']['fields'])) {
        //     $data['header']['form']['fields'] = array_map(function ($field) {
        //         if (isset($field['label'])) {
        //             $base = $this->generateSlug($field['label']);
        //             $field['id'] = 'test';
        //             $field['name'] = $field['name'] ?? $base;
        //         }
        //         return $field;
        //     }, $data['fields']);
        // }
        if ( ! $this->getNestedProperty( 'header.form.name' ) )
        {
            $form_name = $this->generateSlug( $this->getNestedProperty( 'header.form.title' ) );
            $this->setNestedProperty( 'header.form.name', $form_name );
        } else {
            $form_name = $this->generateSlug( $this->getNestedProperty( 'header.form.name' ) );
        }

        if ( ! $this->exists() )
        {
            $this->setNestedProperty( 'header.date', date( 'Y-m-d G:i:s' ) );

            $this->setStorageKey( $form_name );

            if ( $this->checkDuplicateKey( $form_name ) ) {
                parent::save();
            }
        } else if ( $this->getStorageKey() != $this->generateSlug( $this->getNestedProperty( 'header.form.name' ) ) ) {
            $form_name = $this->generateSlug( $this->getNestedProperty( 'header.form.name' ) );
            $this->setStorageKey( $form_name );

            if ( $this->checkDuplicateKey( $form_name ) ) {
                parent::save();
            }
        } else {
            parent::save();
        }

        // $fields = (array)$this->getNestedProperty( 'header.form.fields' );
        //
        // if ( ! empty($fields) ) {
        //     foreach ($fields as $key => $value) {
        //       $slug = $this->generateSlug( $value['label'] );
        //
        //       if ( ! isset($value['id']) ) {
        //         $value['id'] = $slug;
        //       }
        //       $this->setNestedProperty( 'header.form.fields.' . $key . '.id', $slug );
        //     }
        // }

        // $buttons = (array)$this->getNestedProperty( 'header.form.buttons' );
        //
        // if ( ! empty($buttons) ) {
        //     foreach ($buttons as $key => $value) {
        //       $newKey = $this->generateSlug( $value['value'] );
        //
        //       // $getkey = $button[]->getKey();
        //
        //       $getKey = $key;
        //       $key = $newKey;
        //
        //       // $this->setKey( $newKey );
        //       $this->key = $newKey;
        //       // $this->driver->setNamespace($this->key);
        //
        //       // $this->setNestedProperty( 'header.form.buttonkeys.' . $newKey . '.key', $getKey );
        //         // if ( ! $this->hasKey() ) {
        //         //     // $buttonName = $this->generateSlug($buttons[$button]->getProperty('value'));
        //         //     $buttons[$button]->setKey('no_key');
        //         // } else {
        //         //   $button->setKey('has_key');
        //         // }
        //     }
        // }

    }

    /**
     * Convert label to slug
     *
     * @return string
     */
    private function generateSlug(string $label): string
    {
        $slug = Utils::slug($label, true);

        return $slug;
    }

    private function checkDuplicateKey($title)
    {
        $slug = Utils::slug($title, true);
        $original = $this->getOriginalData();
        $original = key_exists('slug', $original) ? $original['slug'] : null;
        $probe = $this->getFlexDirectory()->getObject($slug);

        if ($probe && $slug != $original) {
            $this->setStorageKey($this->generateSlug( $this->getNestedProperty( 'header.form.title' ) . ' ' . time() ));
            $this->setNestedProperty( 'header.form.name', $this->generateSlug( $this->getNestedProperty( 'header.form.title' ) . ' ' . time() ) );
        }
        return true;
    }

}
