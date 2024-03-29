<?php

//src/Wifinder/ProjectBundle/Entity/Project.php

namespace Wifinder\ProjectBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="Wifinder\ProjectBundle\Entity\Repository\ProjectRepository")
 * @Gedmo\TranslationEntity(class="Wifinder\ProjectBundle\Entity\ProjectTranslation")
 * @UniqueEntity(fields="alias", message="Sorry, this alias is already in use.", groups={"Project"})
 */
class Project {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $sort;
    
    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank(message="Please enter alias.", groups={"Project"})
     * @Assert\Regex( 
     *       pattern="/^[a-z,A-Z,\_,\-,0-9]+$/",
     *       message="Alias can contain only letters, numbers and symbols '_' , '-'.", 
     *       groups={"Project"}
     * )
     */
    protected $alias;
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $title;
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     */
    protected $short_description;
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active = true;

    /**
     * @ORM\OneToMany(
     *   targetEntity="ProjectTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;
    
    /**
    * @ORM\OneToMany(
     *   targetEntity="ProjectImage", 
     *   mappedBy="project", 
     *   cascade={"persist", "remove"})
    */
    private $images;
    
    /**
    * @ORM\OneToMany(
     *   targetEntity="ProjectFile", 
     *   mappedBy="project", 
     *   cascade={"persist", "remove"})
    */
    private $files;
    
    /**
     * @ORM\OneToOne(targetEntity="ProjectMeta", 
     *   mappedBy="project", 
     *   cascade={"persist", "remove"})
    */
    protected $meta;

    /**
     * @ORM\ManyToMany(targetEntity="Wifinder\WebItemBundle\Entity\WebItem", inversedBy="join_projects")
     * @ORM\JoinTable(name="webitem_project")
     */
    protected $web_items;
    
    /**
     * @ORM\ManyToOne(
     *   targetEntity="Wifinder\ProjectBundle\Entity\Years", 
     *   inversedBy="project")
     * @ORM\JoinColumn(
     *   name="year_id", 
     *   referencedColumnName="id", 
     *   onDelete="CASCADE")
     */
    protected $years;
    
    protected $action;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
        $this->web_items = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set sort
     *
     * @param integer $sort
     * @return Project
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    
        return $this;
    }

    /**
     * Get sort
     *
     * @return integer 
     */
    public function getSort()
    {
        return $this->sort;
    }
    
    /**
     * Set alias
     *
     * @param string $alias
     * @return Project
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    
        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Project
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set short_description
     *
     * @param string $shortDescription
     * @return Project
     */
    public function setShortDescription($shortDescription)
    {
        $this->short_description = $shortDescription;
    
        return $this;
    }

    /**
     * Get short_description
     *
     * @return string 
     */
    public function getShortDescription()
    {
        return $this->short_description;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Project
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return Project
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;
    
        return $this;
    }

    /**
     * Get is_active
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Add translations
     *
     * @param \Wifinder\ProjectBundle\Entity\ProjectTranslation $translations
     * @return Project
     */
    public function addTranslation(\Wifinder\ProjectBundle\Entity\ProjectTranslation $t)
    {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
        
        return $this;
    }

    /**
     * Remove translations
     *
     * @param \Wifinder\ProjectBundle\Entity\ProjectTranslation $translations
     */
    public function removeTranslation(\Wifinder\ProjectBundle\Entity\ProjectTranslation $translations)
    {
        $this->translations->removeElement($translations);
    }

    /**
     * Get translations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTranslations()
    {
        return $this->translations;
    }
    
    /**
     * Add images
     *
     * @param \Wifinder\ProjectBundle\Entity\ProjectImage $images
     * @return Project
     */
    public function addImage(\Wifinder\ProjectBundle\Entity\ProjectImage $images)
    {
        if (!$this->images->contains($images)) {
            $this->images[] = $images;
            $images->setProject($this);
        }

        return $this;
    }

    /**
     * Remove images
     *
     * @param \Wifinder\ProjectBundle\Entity\ProjectImage $images
     */
    public function removeProjectImage(\Wifinder\ProjectBundle\Entity\ProjectImage $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Remove images
     *
     * @param \Wifinder\ProjectBundle\Entity\ProjectImage $images
     */
    public function removeImage(\Wifinder\ProjectBundle\Entity\ProjectImage $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Add files
     *
     * @param \Wifinder\ProjectBundle\Entity\ProjectFile $files
     * @return Project
     */
    public function addFile(\Wifinder\ProjectBundle\Entity\ProjectFile $files)
    {
        if (!$this->files->contains($files)) {
            $this->files[] = $files;
            $files->setProject($this);
        }
    
        return $this;
    }

    /**
     * Remove files
     *
     * @param \Wifinder\ProjectBundle\Entity\ProjectFile $files
     */
    public function removeFile(\Wifinder\ProjectBundle\Entity\ProjectFile $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set meta
     *
     * @param \Wifinder\ProjectBundle\Entity\ProjectMeta $meta
     * @return Project
     */
    public function setMeta(\Wifinder\ProjectBundle\Entity\ProjectMeta $meta = null)
    {
        $this->meta = $meta;
        $meta->setProject($this);
        
        return $this;
    }

    /**
     * Get meta
     *
     * @return \Wifinder\ProjectBundle\Entity\ProjectMeta 
     */
    public function getMeta()
    {
        return $this->meta;
    }
    
    /**
     * Add web_items
     *
     * @param \Wifinder\WebItemBundle\Entity\WebItem $webItems
     * @return Content
     */
    public function addWebItem(\Wifinder\WebItemBundle\Entity\WebItem $webItems)
    {
        $this->web_items[] = $webItems;
    
        return $this;
    }

    /**
     * Remove web_items
     *
     * @param \Wifinder\WebItemBundle\Entity\WebItem $webItems
     */
    public function removeWebItem(\Wifinder\WebItemBundle\Entity\WebItem $webItems)
    {
        $this->web_items->removeElement($webItems);
    }

    /**
     * Get web_items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWebItems()
    {
        return $this->web_items;
    }
    
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }
    
    public function getAction()
    {
        return $this->action;
    }
    
    public function __toString() {
        if($this->getTitle())
            return $this->getTitle();
        else
            return $this->getAlias();
    }

    /**
     * Set years
     *
     * @param \Wifinder\ProjectBundle\Entity\Years $years
     * @return Project
     */
    public function setYears(\Wifinder\ProjectBundle\Entity\Years $years = null)
    {
        $this->years = $years;
    
        return $this;
    }

    /**
     * Get years
     *
     * @return \Wifinder\ProjectBundle\Entity\Years 
     */
    public function getYears()
    {
        return $this->years;
    }
    
    public function set($field, $value){
        $t = explode('_', $field);
        for($i = 0; $i <= count($t)-1; $i++){
            $t[$i] = ucfirst($t[$i]); 
        }
        call_user_func(array($this, 'set'.implode('', $t)), $value);
        return $this;
    }
}