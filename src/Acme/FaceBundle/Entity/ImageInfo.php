<?php

namespace Acme\FaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImageInfo
 *
 * @ORM\Table("imageInfo")
 * @ORM\Entity
 */
class ImageInfo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="imageId", type="integer")
     */
    private $imageId;

    /**
     * @var integer
     *
     * @ORM\Column(name="imageHeight", type="integer")
     */
    private $imageHeight;

    /**
     * @var integer
     *
     * @ORM\Column(name="imageWidth", type="integer")
     */
    private $imageWidth;

    /**
     * @var string
     *
     * @ORM\Column(name="imageImgId", type="string")
     */
    private $imageImgId;

    /**
     * @var string
     *
     * @ORM\Column(name="sessionId", type="string")
     */
    private $sessionId;

    /**
     * @var text
     *
     * @ORM\Column(name="imageFaceInfo", type="text")
     */
    private $imageFaceInfo;

    /**
     * @var integer
     *
     * @ORM\Column(name="createAt", type="integer")
     */
    private $createAt;

//    /**
//     * @var integer
//     *
//     * @ORM\Column(name="faceAgeRange", type="integer")
//     */
//    private $faceAgeRange;
//
//    /**
//     * @var integer
//     *
//     * @ORM\Column(name="faceAgeValue", type="integer")
//     */
//    private $faceAgeValue;
//
//    /**
//     * @var string
//     *
//     * @ORM\Column(name="faceGenderConfidence", type="string")
//     */
//    private $faceGenderConfidence;
//
//    /**
//     * @var string
//     *
//     * @ORM\Column(name="faceGenderValue", type="string")
//     */
//    private $faceGenderValue;
//
//    /**
//     * @var string
//     *
//     * @ORM\Column(name="faceGlassConfidence", type="string")
//     */
//    private $faceGlassConfidence;
//
//    /**
//     * @var string
//     *
//     * @ORM\Column(name="faceGlassValue", type="string")
//     */
//    private $faceGlassValue;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set imageId
     *
     * @param integer $imageId
     * @return ImageInfo
     */
    public function setImageId($imageId)
    {
        $this->imageId = $imageId;

        return $this;
    }

    /**
     * Get imageId
     *
     * @return integer 
     */
    public function getImageId()
    {
        return $this->imageId;
    }

    /**
     * Set imageHeight
     *
     * @param integer $imageHeight
     * @return ImageInfo
     */
    public function setImageHeight($imageHeight)
    {
        $this->imageHeight = $imageHeight;

        return $this;
    }

    /**
     * Get imageHeight
     *
     * @return integer 
     */
    public function getImageHeight()
    {
        return $this->imageHeight;
    }

    /**
     * Set imageWidth
     *
     * @param integer $imageWidth
     * @return ImageInfo
     */
    public function setImageWidth($imageWidth)
    {
        $this->imageWidth = $imageWidth;

        return $this;
    }

    /**
     * Get imageWidth
     *
     * @return integer 
     */
    public function getImageWidth()
    {
        return $this->imageWidth;
    }

    /**
     * Set imageImgId
     *
     * @param string $imageImgId
     * @return ImageInfo
     */
    public function setImageImgId($imageImgId)
    {
        $this->imageImgId = $imageImgId;

        return $this;
    }

    /**
     * Get imageImgId
     *
     * @return string 
     */
    public function getImageImgId()
    {
        return $this->imageImgId;
    }

    /**
     * Set sessionId
     *
     * @param string $sessionId
     * @return ImageInfo
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * Get sessionId
     *
     * @return string 
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Set imageFaceInfo
     *
     * @param string $imageFaceInfo
     * @return ImageInfo
     */
    public function setImageFaceInfo($imageFaceInfo)
    {
        $this->imageFaceInfo = $imageFaceInfo;

        return $this;
    }

    /**
     * Get imageFaceInfo
     *
     * @return string 
     */
    public function getImageFaceInfo()
    {
        return $this->imageFaceInfo;
    }

    /**
     * Set createAt
     *
     * @param integer $createAt
     * @return ImageInfo
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get createAt
     *
     * @return integer 
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }
}
