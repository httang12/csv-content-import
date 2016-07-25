<?php

namespace Drupal\csv_content_import\Forms;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\node\Entity\Node;

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
        $result = file_save_upload('csvimport_file',['file_validate_extensions' => 'csv'],"public://");

        $realpath = drupal_realpath($result[0]->getFileUri());

        //file saved to public, now start processing

        //Step 1: read the FILE line BY LINE, OH YEAH!
        //Step 2: process each line and create a CONTENT for each! OH YEAH!
        //Step 3: DONE! OH YEAH!

        $file = fopen($realpath,"r");
        while(!feof($file))
        {
            $dataline = fgets($file);
            print_r($dataline);
            $dataArray = explode(",",$dataline);

            // check if site exists, if it does DON'T import
            $siteURL = $dataArray[1];

            // clean up site URL!
            $siteURL = preg_replace('#^https?://#', '', $siteURL);
            $siteURL = preg_replace('/^www\./','',$siteURL);

            $query = \Drupal::entityQuery('node')
                ->condition('type', "legacy_site_config")
                ->condition('field_site_url',$siteURL);

            $nids = $query->execute();
            if (is_array($nids) && count($nids) >0 )
            {
                // don't do anything if the site config exists
                continue;
            }

            // element 0 is site name, element 1 is site url
            $node_elements = array(
                'type' => 'legacy_site_config',
                'title' => $dataArray[0],
                'field_site_url' => $dataArray[1],
                'field_server_path' => $dataArray[2],
            );
            $node = Node::create($node_elements);
            $node->save();
        }

        fclose($file);

        //delete file after processing
        unlink($realpath);

        drupal_set_message(t('Sites are imported'), 'status');
    }
}
?>
