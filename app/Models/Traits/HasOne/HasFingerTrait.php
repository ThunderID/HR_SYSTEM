<?php namespace App\Models\Traits\HasOne;

trait HasFingerTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasFingerTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN FINGER PACKAGE -------------------------------------------------------------------*/

	public function Finger()
	{
		return $this->hasOne('App\Models\Finger');
	}

	public function scopeDisplayUpdatedFinger($query, $variable)
	{
		return $query->selectRaw('hr_contacts.value as email')
					->selectRaw('hr_fingers.person_id')
					->selectRaw('hr_fingers.left_thumb')
					->selectRaw('hr_fingers.left_index_finger')
					->selectRaw('hr_fingers.left_middle_finger')
					->selectRaw('hr_fingers.left_ring_finger')
					->selectRaw('hr_fingers.left_little_finger')
					->selectRaw('hr_fingers.right_thumb')
					->selectRaw('hr_fingers.right_index_finger')
					->selectRaw('hr_fingers.right_middle_finger')
					->selectRaw('hr_fingers.right_ring_finger')
					->selectRaw('hr_fingers.right_little_finger')
					->selectRaw('DATE_FORMAT(hr_fingers.updated_at, "%d/%m/%Y %H:%i:%s") as updated_date')
					->join('contacts', 'persons.id', '=', 'contacts.person_id')
					->join('fingers', 'persons.id', '=', 'fingers.person_id')
					->where('contacts.item', 'email')
					->where('contacts.is_default', true)
					->where('fingers.updated_at', '>', $variable)
					;
	}
}