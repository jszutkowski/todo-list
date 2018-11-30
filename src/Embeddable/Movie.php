<?php
/**
 * User: jszutkowski
 */

namespace App\Embeddable;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Movie
{
    /**
     * @ORM\Column(type="string", name="image_url", nullable=true)
     */
    private $imageUrl;

    /**
     * @return mixed
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @param mixed $imageUrl
     */
    public function setImageUrl($imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }
}