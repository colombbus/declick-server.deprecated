<?php

/*
 * Copyright (C) 2014 Régis
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Description of Uploader
 *
 * @author Régis
 */

namespace Tangara\CoreBundle\Uploader;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class Uploader {

    private $directory;

    public function __construct() {
        $this->directory = '/home/tangara';
                //$this->container->getParameter('tangara_project.uploader.directory');
    }

    public function getUploadDirectory() {
        return $this->directory;
    }

    public function setUploadDirectory($directory) {
        $this->directory = $directory;

        return $this;
    }
}
