<?php
namespace samson\cms\input;

/**
 * Generic SamsonCMS input field
 * @author Vitaly Iegorov<egorov@samsonos.com>
 *
 */
class Image extends File 
{
    /** @var string Identifier */
    protected $id = 'samson_cms_input_file';

    /** Uplaod file controller */
    public function __upload()
    {
        s()->async(true);

        // Create object for uploading file to server
        $upload = new samson\upload\Upload();

        // Uploading file to server;
        $upload->upload();

        // Save path to file in DB
        samson\cms\input\Field::fromMetadata( $_GET['e'], $_GET['f'], $_GET['i'] )->save( 'cms'.$upload->file_path );

        // Return upload object for further usage
        return $upload;
    }

    /** Delete file controller */
    function __delete()
    {
        s()->async(true);

        // Delete path to file from DB
        $field = samson\cms\input\Field::fromMetadata( $_GET['e'], $_GET['f'], $_GET['i'] );

        // Build uploaded file path
        $file = getcwd().samson\upload\Upload::UPLOAD_PATH.basename($field->obj->Value);

        // If uploaded file exists - delete it
        if( file_exists( $file ) ) unlink( $file );

        // Save empty field value
        $field->save( '' );
    }
}