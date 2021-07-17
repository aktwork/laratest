<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Auth;

class UserList extends Component
{
    use WithPagination;
    
    public $sortField = 'name'; // default sorting field
    public $sortAsc = true; // default sort direction
    public $search = '';

    protected $listeners = ['delete'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function delete($id)
    {
        $user = User::find($id);
        if($user->delete()){
            $this->dispatchBrowserEvent('user-deleted', ['user-name'=> $user->name]);
        }
    }
    
    public function render()
    {
        return view('livewire.user.user-list', [
            'users' => User::search($this->search)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->simplePaginate(10),
        ]);
    }
}
