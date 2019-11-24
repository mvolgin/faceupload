<?php
// Laravel 5.4 Upload Image with Validation example Controllers
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\BIOAPI;

class ImageUploadController extends Controller
{
    /**
    * Create view file
    *
    * @return void
    */
	// display upload-image page 
    public function getUploadImage()
    {
        return view('upload-image');
    }

    public function postBioData( Request $request )
    {
        $image = $request->input('image');

        try {
            
            $data = BIOAPI::getData( $image );
            
            return response()->json( $data ); 
            
        } catch ( \Exception $e ) {

            return response()->json(array(
                    'error'   => $e->getMessage()
            ),422); 
        }

    }

    /**
    * Manage Post Request
    *
    * @return void
    */
	// get image from upload-image page 
    public function postUplodeImage( Request $request )
    {
        $this->validate($request, [
			// check validtion for image or file
            'uplode_image_file' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
		// rename image name or file name 
        $getimageName = time().'.'.$request->uplode_image_file->getClientOriginalExtension();
        $request->uplode_image_file->move(public_path('images'), $getimageName);
        return back()
            ->with('success','images Has been You uploaded successfully.')
            ->with('image',$getimageName);
    }
}
