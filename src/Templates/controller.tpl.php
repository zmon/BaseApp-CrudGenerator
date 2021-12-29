<?php

namespace App\Http\Controllers\[[model_uc]];

use App;
use App\Exports\[[model_uc]]Export;
use App\Http\Controllers\Controller;
use App\Http\Requests\[[model_uc]]\[[model_uc]]CreateRequest;
use App\Http\Requests\[[model_uc]]\[[model_uc]]DestroyRequest;
use App\Http\Requests\[[model_uc]]\[[model_uc]]DownloadRequest;
use App\Http\Requests\[[model_uc]]\[[model_uc]]EditRequest;
use App\Http\Requests\[[model_uc]]\[[model_uc]]FormRequest;
use App\Http\Requests\[[model_uc]]\[[model_uc]]IndexRequest;
use App\Http\Requests\[[model_uc]]\[[model_uc]]PrintRequest;
use App\Http\Requests\[[model_uc]]\[[model_uc]]ShowRequest;
use App\Http\Requests\[[model_uc]]\[[model_uc]]HistoryRequest;
use App\Http\Requests\[[model_uc]]\[[model_uc]]HistoryDifferenceRequest;
use App\Http\Resources\[[model_uc]]HistoryResource;
use App\Models\History;
use App\Models\[[model_uc]];
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class [[model_uc]]Controller extends Controller
{

    // =========================================
    // Configurations
    // =========================================


    /**
     * Remember these filters when we return to the index page
     * @return string[]
     */
    static public function getIndexFilters(): array
    {
        return [
            'page' => '',  // Special to pagination
            'keyword' => '',
            'sort_column' => '[[name_field]]',
            'sort_direction' => 'asc',
            'active' => '1', // FILTER SETUP: set defaul
        ];
    }

    /**
     * Return prefix of filter variables for grid
     * @return string
     */
    static public function getIndexFilterKeyPrefix(): string
    {
        return '[[route_path]]';
    }

    /**
     * Columns to be downloaded or printed
     * @var string[]
     */
    private $print_columns = [
[[foreach:grid_columns]]
            '[[tablename]].[[i.name]]',
[[endforeach]]
    ];


    // =========================================
    // Routable Functions
    // =========================================

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index([[model_uc]]IndexRequest $request): View
    {

        $filters = Controller::getIndexFiltersFromSession(
            self::getIndexFilters(),
            self::getIndexFilterKeyPrefix()
        );
        $permissions = $this->getUserPermissions($request->user(), '[[route_path]]');

        return view('[[view_folder]].index', compact('filters', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response;
     */
    public function create([[model_uc]]CreateRequest $request): View
    {
        return view('[[view_folder]].create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @paramRequest $request
     * @return Response;
     */

    /**
     * @param [[model_uc]]FormRequest $request
     * @return Response
     * @throws Exception
     */
    public function store([[model_uc]]FormRequest $request): JsonResponse
    {

        $[[model_singular]] = new [[model_uc]];

        try {
            $[[model_singular]]->add($request->validated());
        } catch (Exception $e) {
            return $this->handleExceptionResponse($e);
        }

        $request->session()->flash('flash_success_message', '[[model_uc]] ' . $[[model_singular]]->[[name_field]] . ' was added.');

        return response()->json([
            'message' => 'Added record',
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param integer $id
     * @return Response
     */
    public function show([[model_uc]]ShowRequest $request, [[model_uc]] $[[model_singular]]): View // make [[model_uc]]ShowRequest to check permissions and redirect
    {

        $relationship_data = $this->getRelationshipData($[[model_singular]]->id);
        $can_edit = $request->user()->can('[[route_path]] edit');
        $can_delete = ($request->user()->can('[[route_path]] delete') && $[[model_singular]]->canDelete());
        $can_history = ($request->user()->can('[[route_path]] history'));

        return view('[[view_folder]].show', compact('[[model_singular]]', 'can_edit', 'can_delete', 'can_history', 'relationship_data'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response;
     */
    public function edit([[model_uc]]EditRequest $request, [[model_uc]] $[[model_singular]]): View // same as show
    {
        $relationship_data = $this->getRelationshipData($[[model_singular]]->id);
        return view('[[view_folder]].edit', compact('[[model_singular]]', 'relationship_data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param App\[[model_uc]] $[[model_singular]] * @return Response;
     */
    public function update([[model_uc]]FormRequest $request, [[model_uc]] $[[model_singular]]): JsonResponse
    {
        $[[model_singular]]->fill($request->validated());

        if ($[[model_singular]]->isDirty()) {

            try {
                $[[model_singular]]->save();
            } catch (Exception $e) {
                return $this->handleExceptionResponse($e, 'Unable to update [[model_uc]] ' . $[[model_singular]]->[[name_field]]);
            }

            $request->session()->flash('flash_success_message', '[[model_uc]] ' . $[[model_singular]]->[[name_field]] . ' was changed.');
        } else {
            $request->session()->flash('flash_info_message', 'No changes were made.');
        }

        return response()->json([
            'message' => 'Changed record',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param App\[[model_uc]] $[[model_singular]] * @return Response;
     */
    public function destroy([[model_uc]]DestroyRequest $request, [[model_uc]] $[[model_singular]]): RedirectResponse
    {

        if ($[[model_singular]]->canDelete()) {

            try {
                $[[model_singular]]->delete();
            } catch (Exception $e) {
                return $this->handleExceptionResponse($e, 'Unable to remove [[model_uc]] ' . $[[model_singular]]->[[name_field]]);
            }

            $request->session()->flash('flash_success_message', '[[model_uc]] ' . $[[model_singular]]->[[name_field]] . ' was removed.');
        } else {
            $request->session()->flash('flash_error_message', 'Unable to remove this [[model_singular]].');

        }

        return Redirect::route('[[route_path]].index');

    }

    /**
     * Display the specified resource.
     *
     * @param integer $id
     * @return Response
     */
    public function history([[model_uc]]HistoryRequest $request, [[model_uc]] $[[model_singular]]): View // make [[model_uc]]ShowRequest to check permissions and redirect
    {

        $[[model_singular]]->load(['histories' => function ($q) {
            return $q->with('user')->orderBy('histories.created_at', 'desc');
        }]);
        $histories = [[model_uc]]HistoryResource::collection(
            $[[model_singular]]->histories->map(function ($h) {
                return [[model_uc]]::formattedHistoryComparison($h);
            })
        );
        $can_history_difference = ($request->user()->can('[[route_path]] history_difference'));

        return view('[[view_folder]].history', compact('[[model_singular]]', 'histories', 'can_history_difference'));

    }

    /**
     * Display the specified resource.
     *
     * @param integer $id
     * @return Response
     */
    public function historyDifference([[model_uc]]HistoryDifferenceRequest $request, [[model_uc]] $[[model_singular]], History $history): View // make [[model_uc]]ShowRequest to check permissions and redirect
    {

        $history->load('user');
        $history->old_user_name = $history->old['modified_by'] ?? null ? User::find($history->old['modified_by'])->[[name_field]] : "N/A";
        $history = [[model_uc]]HistoryResource::make([[model_uc]]::formattedHistoryComparison($history));

        $previous = $[[model_singular]]->histories()
            ->where('created_at', '<', $history->created_at)->orderBy('created_at', 'desc')
            ->first();


        $next = $[[model_singular]]->histories()
            ->where('created_at', '>', $history->created_at)->orderBy('created_at', 'asc')
            ->first();

        $next = $next ? route('[[route_path]].history-difference', ['[[model_singular]]' => $[[model_singular]], 'history' => $next])
            : null;
        $previous = $previous ?
            route('[[route_path]].history-difference', ['[[model_singular]]' => $[[model_singular]], 'history' => $previous])
            : null;


        return view('[[view_folder]].difference', compact('[[model_singular]]', 'history', 'next', 'previous'));

    }


    /**
     * Find by ID, sanitize the ID first.
     *
     * @param $id
     * @return [[model_uc]] or null
     */
    private function getRelationshipData($id): [[model_uc]]
    {
        return [[model_uc]]::find(intval($id));
    }

    public function download([[model_uc]]DownloadRequest $request)
    {

        $filters = Controller::getIndexFiltersFromSession(
            self::getIndexFilters(),
            self::getIndexFilterKeyPrefix()
        );

        $dataQuery = [[model_uc]]::downloadDataQuery($filters, $this->print_columns);

        return Excel::download(
            new [[model_uc]]Export($dataQuery),
            '[[route_path]].xlsx');

    }


    public function print([[model_uc]]PrintRequest $request)
    {

        $filters = Controller::getIndexFiltersFromSession(
            self::getIndexFilters(),
            self::getIndexFilterKeyPrefix()
        );

        $dataQuery = [[model_uc]]::pdfDataQuery($filters, $this->print_columns);

        $data = $dataQuery->get();

        // Pass it to the view for html formatting:
        $printHtml = view('[[view_folder]].print', compact('data'));

        // Begin DOMPDF/laravel-dompdf
        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOptions(['isPhpEnabled' => true]);
        $pdf->loadHTML($printHtml);
        $currentDate = new DateTime();
        return $pdf->stream('[[route_path]]-' . $currentDate->format('Ymd_Hi') . '.pdf');

    }

}
