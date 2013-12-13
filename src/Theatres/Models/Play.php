<?php

namespace Theatres\Models;

class Play extends \RedBean_SimpleModel
{
    const SCENE_MAIN  = 'main';
    const SCENE_SMALL = 'small';
    const SCENE_BIG   = 'big';
    const SCENE_EXP   = 'exp';

    public function update()
    {
        $this->hash = $this->generateHash();
    }

    protected function generateHash()
    {
        $line = sprintf('%s-%s-%s-%s-%s',
            $this->theatre,
            $this->date,
            $this->title,
            $this->scene,
            $this->link
        );
        return md5($line);
    }
}