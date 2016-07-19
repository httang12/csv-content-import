<?php

namespace Drupal\csv_content_import\Forms;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;

/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 7/18/16
 * Time: 10:58 AM
 */
/**
 * Contribute form.
 */
class CSVImportForm extends FormBase {
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return "simple-csv-import-content-form";
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {

        $form['csvimport_file'] = array(
            '#type' => 'file',
            '#title' => t('CSV Upload'),
            '#description' => t('Upload a file, allowed extensions: csv'),
        );

        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => t('Submit'),
        );

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {

        $file = $form_state['storage']['csvimport_file'];
        // We are done with the file, remove it from storage.

        print_r($file);die;
        //never save file
        unset($form_state['storage']['csvimport_file']);
        //process file
    }
}
?>
