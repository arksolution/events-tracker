<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Location extends Eloquent {


    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d\\TH:i';

	/**
	 * @var Array
	 *
	 **/
	protected $fillable = [
	'name', 'slug', 'attn', 'address_one', 'address_two', 'neighborhood', 'city', 'state', 'postcode', 'country', 'latitude', 'longitude', 'location_type_id', 'entity_id', 'capacity','map_url'
	];

 
	protected $dates = ['created_at','updated_at'];

	
	/**
	 * Get the entities that belong to the tag
	 *
	 * @ return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function entities()
	{
		return $this->belongsToOne('App\Entity')->withTimestamps();
	}

	/**
	 * A location has one type
	 *
	 */
	public function locationType()
	{
		return $this->hasOne('App\LocationType','id','location_type_id');
	}

}
