<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyConfigRequest;
use App\Http\Requests\StoreConfigRequest;
use App\Http\Requests\UpdateConfigRequest;
use App\Models\Config;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ConfigController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('config_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $configs = Config::with(['media'])->get();

        return view('admin.configs.index', compact('configs'));
    }

    public function create()
    {

        abort_if(Gate::denies('config_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.configs.create');
    }

    public function store(StoreConfigRequest $request)
    {

        $config = Config::create($request->all());

        if ($request->input('attachment', false)) {
            $config->addMedia(storage_path('tmp/uploads/' . basename($request->input('attachment'))))->toMediaCollection('attachment');
        }

        if ($media = $request->input('ck-media', false)) {

            Media::whereIn('id', $media)->update(['model_id' => $config->id]);
        }
        return redirect()->route('admin.configs.index');
    }

    public function edit(Config $config)
    {
        abort_if(Gate::denies('config_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.configs.edit', compact('config'));
    }

    public function update(UpdateConfigRequest $request, Config $config)
    {
        $config->update($request->all());

        if ($request->input('attachment', false)) {
            if (!$config->attachment || $request->input('attachment') !== $config->attachment->file_name) {
                if ($config->attachment) {
                    $config->attachment->delete();
                }
                $config->addMedia(storage_path('tmp/uploads/' . basename($request->input('attachment'))))->toMediaCollection('attachment');
            }
        } elseif ($config->attachment) {
            $config->attachment->delete();
        }

        return redirect()->route('admin.configs.index');
    }

    public function show(Config $config)
    {
        abort_if(Gate::denies('config_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.configs.show', compact('config'));
    }

    public function destroy(Config $config)
    {
        abort_if(Gate::denies('config_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $config->delete();

        return back();
    }

    public function massDestroy(MassDestroyConfigRequest $request)
    {
        Config::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('config_create') && Gate::denies('config_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model = new Config();
        $model->id = $request->input('crud_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
