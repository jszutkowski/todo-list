<?php
/**
 * User: jszutkowski
 */

namespace App\Embeddable;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Book
{
    /**
     * @ORM\Column(type="string", name="review_url", nullable=true)
     */
    private $reviewUrl;

    /**
     * @return mixed
     */
    public function getReviewUrl(): ?string
    {
        return $this->reviewUrl;
    }

    /**
     * @param mixed $reviewUrl
     */
    public function setReviewUrl($reviewUrl): void
    {
        $this->reviewUrl = $reviewUrl;
    }


}