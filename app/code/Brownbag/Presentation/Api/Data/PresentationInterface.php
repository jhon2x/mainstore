<?php

namespace Brownbag\Presentation\Api\Data;

/**
 * @api
 */
interface PresentationInterface
{   

    const ID = 'repository_log_id';
    const DATE = 'repository_date';
    const CONTENT = 'repository_content';
    const IMAGE = 'image';

    /**
     * @param int $id
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getId();

    /**
     * @param string $date
     */
    public function setRepositoryDate($date);

    /**
     * @return string
     */
    public function getRepositoryDate();

    /**
     * @param string $content
     */
    public function setRepositoryContent($content);

    /**
     * @return string
     */
    public function getRepositoryContent();

    /**
     * Get image
     *
     * @return string
     */
    public function getImage();

    /**
     * Set image
     *
     * @param $image
     */
    public function setImage($image);
}