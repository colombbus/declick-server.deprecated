<?php
namespace Declick\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping\AttributeOverrides;
use Doctrine\ORM\Mapping\AttributeOverride;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Declick\CoreBundle\Entity\UserRepository")
 * @ORM\Table(name="users")
 * 
 * @AttributeOverrides({
 *     @AttributeOverride(name="emailCanonical",
 *         column=@ORM\Column(
 *             name="emailCanonical",
 *             type="string",
 *             length=255,
 *             unique=false,
 *             nullable=true
 *         )
 *     ),
 *     @AttributeOverride(name="email",
 *         column=@ORM\Column(
 *             name="email",
 *             type="string",
 *             length=255,
 *             nullable=true
 *         )
 *     )

 * })
 * 
 * 
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var integer
     * 
     * @ORM\Column(type="bigint", nullable=true, unique=true)
     */
    protected $externalId;

    
    /**
     * @ORM\OneToOne(targetEntity="Project")
     */
    private $home;
    
    
    /**
     * @var datetime
     *
     * @ORM\Column(name="polling", type="datetime", nullable=true)
     *
     */
    protected $polling;

    /**
     * Set home
     *
     * @param \Declick\CoreBundle\Entity\Project $home
     * @return User
     */
    public function setHome(\Declick\CoreBundle\Entity\Project $home = null)
    {
        $this->home = $home;

        return $this;
    }

    /**
     * Get home
     *
     * @return \Declick\CoreBundle\Entity\Project 
     */
    public function getHome()
    {
        return $this->home;
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
     * Set polling
     *
     * @param \DateTime $polling
     * @return User
     */
    public function setPolling($polling)
    {
        $this->polling = $polling;

        return $this;
    }

    /**
     * Get polling
     *
     * @return \DateTime 
     */
    public function getPolling()
    {
        return $this->polling;
    }

    /**
     * Set externalId
     *
     * @param integer $externalId
     *
     * @return User
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * Get externalId
     *
     * @return integer
     */
    public function getExternalId()
    {
        return $this->externalId;
    }
}
