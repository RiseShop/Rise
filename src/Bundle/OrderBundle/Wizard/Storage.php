<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\OrderBundle\Wizard;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Storage
{
    const PREFIX = '__wizard';

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * Storage constructor.
     *
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param int|string $step
     *
     * @return array
     */
    public function get($step)
    {
        return $this->session->get(sprintf('%s/%s', self::PREFIX, $step), []);
    }

    /**
     * @param int|string $step
     * @param array      $parameters
     */
    public function set($step, array $parameters)
    {
        $this->session->set(sprintf('%s/%s', self::PREFIX, $step), $parameters);
    }

    public function clean()
    {
        $this->session->remove(self::PREFIX);
    }

    /**
     * @return array
     */
    public function all()
    {
        $data = [];
        foreach ($this->session->all() as $key => $value) {
            if (strpos($key, self::PREFIX) === 0) {
                $data[str_replace(self::PREFIX.'/', '', $key)] = $value;
            }
        }

        return $data;
    }

    /**
     * @return array
     */
    public function merge()
    {
        $data = [];
        foreach ($this->all() as $item) {
            $data = array_merge($data, $item);
        }

        return $data;
    }
}
