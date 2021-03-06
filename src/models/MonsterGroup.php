<?php

class MonsterGroup implements Robbo\Presenter\PresentableInterface
{
    use MagicModel;

    protected $data;
    protected $repo;

    public function __construct(Repositories\RepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function load($data)
    {
        $this->data = $data;
    }

    public function getPresenter()
    {
        return new Presenters\MonsterGroup($this);
    }

    public function getUniqueMonsters()
    {
        $monsters = array();
        if (isset($this->data->monsters) && is_array($this->data->monsters) && count($this->data->monsters)>0) {
            foreach ($this->data->monsters as $monster) {
                $monster = $monster->monster;
                $monsters[$monster] = true;
            }
        }
        $uniqueMonsters = array_keys($monsters);

        return array_map(function (&$monster) {
            return $this->repo->getModel('Monster', $monster);
        }, $uniqueMonsters);
    }

    public function getId()
    {
        return $this->data->id;
    }
}
