<?php

namespace Modules\Penghargaan\Http\Controllers;

use Modules\Core\Http\Controllers\CoreController as Controller;
use Illuminate\Http\Request;

use Modules\Penghargaan\Entities\Penghargaan;
use Modules\Penghargaan\Http\Requests\PenghargaanRequest;

class PenghargaanController extends Controller
{
    protected $title;

    /**
     * Siapkan konstruktor controller
     * 
     * @param Penghargaan $data
     */
    public function __construct(Penghargaan $data) 
    {
        $this->title = __('penghargaan::general.title');
        $this->data = $data;

        $this->toIndex = route('epanel.penghargaan.index');
        $this->prefix = 'epanel.penghargaan';
        $this->view = 'penghargaan::penghargaan';

        $this->tCreate = __('penghargaan::general.created', ['title' => $this->title]);
        $this->tUpdate = __('penghargaan::general.updated', ['title' => $this->title]);
        $this->tDelete = __('penghargaan::general.deleted', ['title' => $this->title]);

        view()->share([
            'title' => $this->title, 
            'view' => $this->view, 
            'prefix' => $this->prefix
        ]);
    }

    /**
     * Tampilkan halaman utama modul yang dipilih
     * 
     * @param Request $request
     * @return Response|View
     */
    public function index(Request $request) 
    {
        $data = $this->data->latest()->get();

        if($request->has('datatable')):
            return $this->datatable($data);
        endif;

        return view("$this->view.index", compact('data'));
    }

    /**
     * Tampilkan halaman untuk menambah data
     * 
     * @return Response|View
     */
    public function create() 
    {
        return view("$this->view.create");
    }

    /**
     * Lakukan penyimpanan data ke database
     * 
     * @param Request $request
     * @return Response|View
     */
    public function store(PenghargaanRequest $request) 
    {
        $input = $request->all();

        $input['id_operator'] = optional(auth()->user())->id;

        if($request->hasFile('icon')):
            $input['icon'] = $this->upload($request->file('icon'), uuid());
        endif;

        $this->data->create($input);

        notify()->flash($this->tCreate, 'success');
        return redirect($this->toIndex);
    }

    /**
     * Menampilkan detail lengkap
     * 
     * @param Int $id
     * @return Response|View
     */
    public function show($id)
    {
        return abort(404);
    }

    /**
     * Tampilkan halaman perubahan data
     * 
     * @param Int $id
     * @return Response|View
     */
    public function edit($id)
    {
        $edit = $this->data->uuid($id)->firstOrFail();
    
        return view("$this->view.edit", compact('edit'));
    }

    /**
     * Lakukan perubahan data sesuai dengan data yang diedit
     * 
     * @param Request $request
     * @param Int $id
     * @return Response|View
     */
    public function update(PenghargaanRequest $request, $id)
    {    
        $edit = $this->data->uuid($id)->firstOrFail();

        $input = $request->all();

        if($request->hasFile('icon')):          
            deleteImg($edit->icon);
            $input['icon'] = $this->upload($request->file('icon'), uuid());
        else:
            $input['icon'] = $edit->icon;
        endif;
        
        $edit->update($input);

        notify()->flash($this->tUpdate, 'success');
        return redirect($this->toIndex);
    }

    /**
     * Lakukan penghapusan data yang tidak diinginkan
     * 
     * @param Request $request
     * @param Int $id
     * @return Response|String
     */
    public function destroy(Request $request, $id)
    {
        if($request->has('pilihan')):
            foreach($request->pilihan as $temp):
                $each = $this->data->uuid($temp)->firstOrFail();
                deleteImg($each->icon);
                $each->delete();
            endforeach;
            notify()->flash($this->tDelete, 'success');
            return redirect()->back();
        endif;
    }

    /**
     * Function for Upload File
     * 
     * @param  $file, $filename
     * @return URI
     */
    public function upload($file, $filename) 
    {
        $tmpFilePath = 'app/public/Penghargaan/';
        $tmpFileDate =  date('Y-m') .'/'.date('d').'/';
        $tmpFileName = $filename;
        $tmpFileExt = $file->getClientOriginalExtension();

        makeImgDirectory($tmpFilePath . $tmpFileDate);
        
        $nama_file = $tmpFilePath . $tmpFileDate . $tmpFileName;
        
        \Image::make($file->getRealPath())->resize(500, null, function($constraint) {
            $constraint->aspectRatio();
        })->save(storage_path() . "/$nama_file.$tmpFileExt");
        
        \Image::make($file->getRealPath())->fit(150, 150)->save(storage_path() . "/{$nama_file}_m.$tmpFileExt");

        return "storage/Penghargaan/{$tmpFileDate}{$tmpFileName}.{$tmpFileExt}";
    }

    /**
     * Datatable API
     * 
     * @param  $data
     * @return Datatable
     */
    public function datatable($data) 
    {
        return datatables()->of($data)
            ->editColumn('pilihan', function($data) {
                $return  = '<span>';
                $return .= '    <div class="checkbox checkbox-only">';
                $return .= '        <input type="checkbox" id="pilihan['.$data->id.']" name="pilihan[]" value="'.$data->uuid.'">';
                $return .= '        <label for="pilihan['.$data->id.']"></label>';
                $return .= '    </div>';
                $return .= '</span>';
                return $return;
            })
            ->editColumn('icon', function($data) {
                return '<a href="'. viewImg($data->icon) . '" data-lity><img src="'. viewImg($data->icon, 'm') . '" class="img-responsive img-thumbnail" width="50px"></a>';
            })
            ->editColumn('label', function($data) {
                $return  = $data->label;
                $return .= '<div class="font-11 color-blue-grey-lighter">';
                $return .=      $data->konten;
                $return .= '</div>';
                return $return;
            })
            ->editColumn('tanggal', function($data) {
                \Carbon\Carbon::setLocale('id');
                $return  = '<small>' . date('Y-m-d h:i:s', strtotime($data->created_at)) . '</small><br/>';
                if($data->created_at):
                    $return .= str_replace('yang ', '', $data->created_at->diffForHumans());
                endif;
                return $return;
            })
            ->editColumn('oleh', function($data) {
                return '<img src="' . \Avatar::create(optional($data->operator)->nama)->toBase64() . '" data-toggle="tooltip" data-placement="top" data-original-title="Posted by ' . optional($data->operator)->nama . '">';
            })
            ->editColumn('aksi', function($data) {
                $return  = '<div class="btn-toolbar">';
                $return .= '    <div class="btn-group btn-group-sm">';
                $return .= '        <a href="'. route("$this->prefix.edit", $data->uuid) .'" role="button" class="btn btn-sm btn-primary-outline">';
                $return .= '            <span class="fa fa-pencil"></span>';
                $return .= '        </a>';
                $return .= '    </div>';
                $return .= '</div>';
                return $return;
            })
            ->rawColumns(['pilihan', 'icon', 'label', 'tanggal', 'oleh', 'aksi'])->toJson();
    }
}
