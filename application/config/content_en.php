<?php

$config['about_us']               = 'About Us';

$config['content_management'] 		= array(
        //LINK NAME                     // VALUE NAME (For User Interface)
		'Admin_Management'            => 'Admin Management',
		'Carousel_Management'         => 'Carousel Management',
    'Package_Management'          => 'Package Management',
    'Product_Management'          => 'Product Management',
    'Voucher_Management'          => 'Voucher Management',
		'v2/Order_Management'			  		=> 'Order Management',
		'v2/Trip_Management'			  			=> 'Trip Database',
		'v2/Invoice_Management'    			=> 'Invoice Management',
		'v2/Period_Management'						=> 'Payment Period',
		'v2/Private_Trip_Management'			=> 'Private Trip Management',
		'Payment_Method_Management'	  => 'Payment Method Management',
		'Age_Group_Management'		  	=> 'Age Group Management',
    'About_Management'            => 'About Management',
    'Experience_Management'       => 'Experience Management',
		'Contact_Management'          => 'Contact Management',
		'Media_Management'            => 'Media Management',
    'Footer_Management'           => 'Footer Management',
	'TNC_Management'								=> 'T&C Management'
);

$config['Admin_Management_List']  = array(
		'admin_id'                  	=> 'Admin ID',
		'username'                    => 'Admin name',
    'last_login'                  => 'Last Login',
);
$config['TNC_Management_List']  = array(
    'tnc_id'                  	=> 'TNC ID',
    'tnc_content'                    => 'Content',
);

$config['Carousel_Management_List']  = array(
    'carousel_id'                  	 => 'Carousel ID',
		'carousel_name'               	 => 'Carousel Name',
//    'carousel_description'        	 => 'Carousel Description',
    'carousel_layout'             	 => 'Carousel Layout',
    'carousel_image'              	 => 'Carousel Image',
);

$config['Package_Management_List']   = array(
    'package_id'               		 	 => 'Package ID',
		'package_name'               		 => 'Package Name',
    'package_description'        		 => 'Package Description',
    'package_image'              		 => 'Package Image',
);

$config['Product_Management_List']   = array(
    'product_id'               		 	 => 'Product ID',
		'product_name'               		 => 'Product Name',
    'product_cover_image'        		 => 'Product Cover Image',
		'product_image'        	 		 		 => 'Product Image',
		'package_name'        			 		 => 'Package Name',
    'product_display_price'        		 => 'Product Display Price',
    'product_price'              		 => 'Product Price',
		'product_duration'           		 => 'Product Duration',
	'product_highlight'				    => 'Product Highlight',
		'product_order_type'       		 => 'Available Order Type',
		'product_registration_date'  		 => 'Product Registration Date'
);

$config['About_Management_List']  	 = array(
    'about_id'               		 	 	 => 'About ID',
    'about_description'        			 => 'About Description',
    'about_image'              			 => 'About Image',
);

$config['Experience_Management_List']= array(
    'experience_id'               	 => 'Experience ID',
    'experience_name'        				 => 'Experience Name',
		'experience_description'         => 'Experience Description',
    'experience_image'            	 => 'Experience Image',
);

$config['Contact_Management_List']	 = array(
    'contact_id'               	 		 => 'Contact ID',
    'contact_name'        					 => 'Contact Name',
    'contact_image'               	 => 'Contact Image',
);

$config['Media_Management_List']  	 = array(
    'media_id'               	 		 	 => 'Media ID',
    'media_link'        						 => 'Social Media Link',
    'media_image'              	 		 => 'Social Media Image',
);

$config['Footer_Management_List'] 	 = array(
    'footer_id'               	 		 => 'Footer ID',
    'footer_image'               		 => 'Footer Image',
);

$config['Voucher_Management_List'] 	 = array(
    'voucher_id'               	 		 => 'Voucher ID',
    'voucher_code'               		 => 'Voucher Code',
    'voucher_amount'               		 => 'Voucher Amount',
    'voucher_quota'               		 => 'Voucher Quota',
	'voucher_deactivated_at'			 => 'Active',
    'voucher_expiration_date'            => 'Expiration Date',
	'voucher_product'					 => 'Product'
);

$config['Order_Management_List'] = [
    'order_id'            => 'Order ID',
    'product_name'        => 'Product Name',
    'order_name'          => 'Name',
    'order_phone'         => 'Phone',
    'order_email'         => 'Email',
    'order_line_id'       => 'Line ID',
	'order_participant'	  => 'Participant',
    'voucher_code'        => 'Voucher Code',
    'order_type_name'     => 'Order Type',
    'order_price'         => 'Price',
    'payment_method_name' => 'Payment Method',
    'order_start_date'    => 'Private Start Date',
    'order_end_date'      => 'Private End Date',
    'order_note'          => 'Private Note',
	'created_at'	 	  => 'Created At',
];

$config['Age_Group_Management_List'] = [
	'age_group_id'	=> 'Age Group Id',
	'age_group_name'=> 'Age Group Name',
	'age_group_description' => 'Age Group Description'
];
$config['Payment_Method_Management_List'] = [
	'payment_method_id'	=> 'Payment Method Id',
	'payment_method_name'=> 'Payment Method Name',
	'payment_method_description' => 'Payment Method Description'
];

$config['Admin_Management_Form']  									=  array(
    'Admin Name'                  									=> array(
			'username'																		=> array(
				'<input type="text" name="username" class="form-control"
				id="username" placeholder="Admin Name" value="[[val]]" />'
																										=> 'required'
			),
		),
		'Password'                  										=> array(
			'password'																		=> array(
				'<input type="text" name="password" class="form-control"
				id="password" placeholder="Password" value="[[val]]" />'
																										=> 'required'
			),
		),

);
$config['TNC_Management_Form']  									=  array(
    'TNC Content'                  							=> array(
        'tnc_content'															=> array(
            '[[textarea]]'																=> 'required'
        ),
    ),

);

$config['Carousel_Management_Form']  									=  array(
    'Carousel Name'                  									=> array(
			'carousel_name'																	=> array(
				'<input type="text" name="carousel_name" class="form-control"
				id="carousel_name" placeholder="Carousel Name" value="[[val]]" />'
																											=> 'required'
			),
		),
		/*
		'Carousel Description'                  					=> array(
			'carousel_description'													=> array(
				'<input type="text" name="carousel_description" class="form-control"
				id="carousel_description" placeholder="Carousel Description" value="[[val]]" />'
																											=> 'required'
			),
		),*/
		'Carousel Link'                  								=> array(
			'carousel_layout'																=> array(
				'<input type="text" name="carousel_layout" class="form-control"
				id="carousel_layout" placeholder="Carousel Link (Ex: http://www.peponitravel.com)" value="[[val]]" />'
																											=> 'required'
			),
		),
		'Carousel Image'                  								=> array(
			'carousel_image'																=> array(
				'<input type="file" name="carousel_image" class="form-control"
				id="carousel_image" placeholder="Carousel Image" />'
																											=> 'required'
			),
		),
);

$config['Package_Management_Form']  									=  array(
    'Package Name'                  									=> array(
			'package_name'																	=> array(
				'<input type="text" name="package_name" class="form-control"
				id="package_name" placeholder="Package Name" value="[[val]]" />'
																											=> 'required'
			),
		),
		'Package Description'                  						=> array(
			'package_description'														=> array(
				'<input type="text" name="package_description" class="form-control"
				id="package_description" placeholder="Package Description" value="[[val]]" />'
																											=> 'required'
			),
		),
		'Package Image'                  									=> array(
			'package_image'																	=> array(
				'<input type="file" name="package_image" class="form-control"
				id="package_image" placeholder="Package Image" />'
																											=> 'required'
			),
		),
);

$config['Product_Management_Form']  									=  array(
    'Product Name'                  									=> array(
			'product_name'																	=> array(
				'<input type="text" name="product_name" class="form-control"
				id="product_name" placeholder="Product Name" value="[[val]]" />'
																											=> 'required'
			),
		),
		'Product Cover Image'                  						=> array(
			'product_cover_image'														=> array(
				'<input type="file" name="product_cover_image" class="form-control"
				id="product_cover_image" placeholder="Product Cover Image" />'
																											=> 'required'
			),
		),
		'Package Name'                  								=> array(
			'package_id'																	=> array(
				'<select name="package_id" class="form-control">
				 	<option value="">Please Select Option</option>
					[[option]]
				 </select>'
																											=> 'required'
			),
		),
		'Product Duration'                  							=> array(
			'product_duration'															=> array(
				'<input type="text" name="product_duration" class="form-control"
				id="product_duration" placeholder="Product Duration" value="[[val]]" />'
																											=> 'required'
			),
		),
		'Product Highlight'                  							=> array(
			'product_highlight'															=> array(
				'<textarea type="text" name="product_highlight" class="form-control"
				id="product_highlight" placeholder="Product Highlight" rows="3">[[val]]</textarea>'
																											=> 'required'
			),
		),
    'Product Airlines'                  							=> array(
        'product_airlines'															=> array(
            '<input type="text" name="product_airlines" class="form-control"
				id="product_airlines" placeholder="Product Airlines" value="[[val]]" />'
            => 'required'
        ),
    ),
    'Product Include'                  									=> array(
        'product_include'																	=> array(
            '<input type="text" name="product_include" class="form-control"
				id="product_include" placeholder="Product Include" value="[[val]]" />'
            => 'required'
        ),
    ),
    'Product Exclude'                  							  => array(
        'product_exclude'															  => array(
            '<input type="text" name="product_exclude" class="form-control"
				id="product_exclude" placeholder="Product Exclude" value="[[val]]" />'
            => 'required'
        ),
    ),

    'Available Order Type'                  								=> array(
        'product_order_type'																	=> array(
            '<select name="product_order_type" class="form-control">
				 	<option value="">Please Select Option</option>
					[[orderTypeOption]]
				 </select>'
            => 'required'
        ),
    ),
    'Product Rundown Tour'                  					=> array(
        'product_rundown_tour'													=> array(
            '[[textarea]]'																=> 'required'
        ),
    ),
    'Product Display Price'                  							  => array(
        'product_price'															  => array(
            '<input type="text" name="product_price" class="form-control"
				id="product_price" placeholder="Product Display Price" value="[[val]]" />'
            => 'required'
        ),
    ),
);

//'product_image'        	 		 		 => 'Product Image',

$config['About_Management_Form']  										=  array(
		'About Description'                  							=> array(
			'about_description'															=> array(
				'[[textarea]]'																=> 'required'
			),
		),
		'About Image'                  										=> array(
			'about_image'																		=> array(
				'<input type="file" name="about_image" class="form-control"
				id="about_image" placeholder="About Image" />'
																											=> 'required'
			),
		),
);

$config['Experience_Management_Form']  								=  array(
		'Experience Name'                  							  => array(
			'experience_name'															  => array(
				'<input type="text" name="experience_name" class="form-control"
				id="experience_name" placeholder="Experience Name" value="[[val]]" />'
																											=> 'required'
			),
		),
		'Experience Description'                  				=> array(
			'experience_description'												=> array(
				'[[textarea]]'																=> 'required'
			),
		),
		'Experience Image'                  										=> array(
			'experience_image'																		=> array(
				'<input type="file" name="experience_image" class="form-control"
				id="experience_image" placeholder="Experience Image" />'
																											=> 'required'
			),
		),
);

$config['Contact_Management_Form']  								=  array(
		'Contact Name'                  							  => array(
			'contact_name'															  => array(
				'<input type="text" name="contact_name" class="form-control"
				id="contact_name" placeholder="Contact Name" value="[[val]]" />'
																										=> 'required'
			),
		),
		'Contact Image'                  								=> array(
			'contact_image'																=> array(
				'<input type="file" name="contact_image" class="form-control"
				id="contact_image" placeholder="Contact Image" />'
																										=> 'required'
			),
		),
);

$config['Media_Management_Form']  									=  array(
		'Social Media Link'                  						=> array(
			'media_link'															  	=> array(
				'<input type="text" name="media_link" class="form-control"
				id="media_link" placeholder="Media Link" value="[[val]]" />'
																										=> 'required'
			),
		),
		'Social Media Image'                  					=> array(
			'media_image'																	=> array(
				'<input type="file" name="media_image" class="form-control"
				id="media_image" placeholder="Media Image" />'
																										=> 'required'
			),
		),
);

$config['Footer_Management_Form']  									=  array(
		'Footer Image'                  								=> array(
			'footer_image'																=> array(
				'<input type="file" multiple name="footer_image[]" class="form-control"
				id="footer_image" placeholder="Footer Image" />'
																										=> 'callback_check_image'
			),
		),
);

$config['Tr_Product_Image_Management_Form']  									=  array(
		'Product Image'                  								=> array(
			'product_image'																=> array(
				'<input type="file" multiple name="product_image[]" class="form-control"
				id="product_image" placeholder="Product Image" />'
																										=> 'callback_check_image'
			),
		),
);

$config['Age_Group_Management_Form'] = [
	'Age Group Name' => [
		'age_group_name' => [
            '<input type="text" name="age_group_name" class="form-control"
				id="age_group_name" placeholder="Age Group Name" value="[[val]]" />'  => 'required'
		]
	],
	'Age Group Description' => [
		'age_group_description' => [
            '<input type="text" name="age_group_description" class="form-control"
				id="age_group_description" placeholder="Age Group Description" value="[[val]]" />'  => 'required'
		]
	]
];

$config['Payment_Method_Management_Form'] = [
	'Payment Method Name' => [
		'payment_method_name' => [
            '<input type="text" name="payment_method_name" class="form-control"
				id="payment_method_name" placeholder="Payment Method Name" value="[[val]]" />'  => 'required'
		]
	],
	'Payment Method Description' => [
		'payment_method_description' => [
            '[[textarea]]'																=> 'required'
		]
	]
];

/*
$config['Admin_Management_Form']  = array(
    'Admin name'                  =>  array(
        'name'                    =>  'username',
        'id'                      =>  '',
        'type'                    =>  'text',
        'style'                   =>  '',
        'constraint'              =>  '',
    ),
    'Last Login'                  => array(
        'name'                    =>  'last_login',
        'id'                      =>  '',
        'type'                    =>  'text',
        'style'                   =>  '',
        'constraint'              =>  '',
    ),
);
*/

?>
