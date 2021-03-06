<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TutorialRepository")
 */
class Tutorial
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank()
     */
    private $title;

     /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @var string
     */
    private $content;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade={"all"}, orphanRemoval=true)
     * @var ?\App\Entity\Image
     */
    private $image;

    /**
     * @var ?\App\Entity\User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tutorials")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="tutorials")
     * @Assert\NotBlank()
     * @var ?\Doctrine\Common\Collections\ArrayCollection
     */
    private $categories;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @var bool
     */
    private $published;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var ?\DateTime
     */
    private $dateCreate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var ?\DateTime
     */
    private $dateUpdate;

    /*
     * @ORM\OneToMany(targetEntity="App\Entity\TutorialFollow", mappedBy="tutorial", orphanRemoval=true)
     */
    private $followers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="tutorial", orphanRemoval=true)
     * @var ?\Doctrine\Common\Collections\ArrayCollection
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rating", mappedBy="tutorials", orphanRemoval=true)
     */
    private $userRate;

    /**
     * @ORM\Column(type="decimal", scale=1, nullable=true)
     */
    private $averageRating;

    
    public function __construct()
    {
        $this->dateCreate = new \DateTime;
        $this->dateUpdate = null;
        $this->followers = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->userRate = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of content
     *
     * @return  string
     */ 
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @param  string  $content
     *
     * @return  self
     */ 
    public function setContent(string $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of image
     *
     * @return  ?\App\Entity\Image
     */ 
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @param  ?\App\Entity\Image  $image
     *
     * @return  self
     */ 
    public function setImage(?\App\Entity\Image $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get the value of user
     *
     * @return  ?\App\Entity\User
     */ 
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @param  ?\App\Entity\User  $user
     *
     * @return  self
     */ 
    public function setUser(?\App\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of categories
     *
     * @return  ?\Doctrine\Common\Collections\ArrayCollection
     */ 
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set the value of categories
     *
     * @param  ?\Doctrine\Common\Collections\ArrayCollection  $categories
     *
     * @return  self
     */ 
    public function setCategories(?\Doctrine\Common\Collections\ArrayCollection $categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get the value of published
     *
     * @return  bool
     */ 
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set the value of published
     *
     * @param  bool  $published
     *
     * @return  self
     */ 
    public function setPublished(bool $published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get the value of dateCreate
     *
     * @return  ?\DateTime
     */ 
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set the value of dateCreate
     *
     * @param  ?\DateTime  $dateCreate
     *
     * @return  self
     */ 
    public function setDateCreate(?\DateTime $dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get the value of dateUpdate
     *
     * @return  ?\DateTime
     */ 
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * Set the value of dateUpdate
     *
     * @param  ?\DateTime  $dateUpdate
     *
     * @return  self
     */ 
    public function setDateUpdate(?\DateTime $dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * @return Collection|TutorialFollow[]
     */
    public function getFollowers(): ?Collection
    {
        return $this->followers;
    }
    
    public function addFollower(TutorialFollow $follower): self
    {
        if ($this->followers->contains($follower)) {
            $this->followers->removeElement($follower);
            // set the owning side to null (unless already changed)
            if ($follower->getTutorial() === $this) {
                $follower->setTutorial(null);
            }
        }
        
        return $this;
    }
    
    public function removeFollower(TutorialFollow $follower): self
    {
        if ($this->followers->contains($follower)) {
            $this->followers->removeElement($follower);
            // set the owning side to null (unless already changed)
            if ($follower->getTutorial() === $this) {
                $follower->setTutorial(null);
            }
        }
        
        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setTutorial($this);
        }
 
        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getTutorial() === $this) {
                $comment->setTutorial(null);
            }
        }
 
        return $this;
    }

    /**
     * Get the value of averageRating
     */ 
    public function getAverageRating()
    {
        return $this->averageRating;
    }

    /**
     * Set the value of averageRating
     *
     * @return  self
     */ 
    public function setAverageRating($averageRating)
    {
        $this->averageRating = $averageRating;

        return $this;
    }

    public function calculateAverage()
    {
        $total = 0;
        foreach($this->userRate as $rate) {
            $total += $rate->getRating();
        }

        $this->averageRating = $total/ count($this->userRate);
    }

    /**
     * Get the value of userRate
     */ 
    public function getUserRate()
    {
        return $this->userRate;
    }

    /**
     * Set the value of userRate
     *
     * @return  self
     */ 
    public function setUserRate($userRate)
    {
        $this->userRate = $userRate;

        return $this;
    }

    public function addUserRate(Rating $userRate)
    {
        if (!$this->userRate->contains($userRate)) {
            $this->userRate[] = $userRate;
        } else {
            $this->userRate[$this->userRate->indexOf($userRate)]->setRating($userRate->getRating());
        }
    }
}

