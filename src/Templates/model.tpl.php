<?php

namespace App\Models;

use App\Lib\DifferenceHelper;
use App\Traits\HistoryTrait;
use App\Traits\RecordSignature;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class [[model_uc]] extends Model
{

    use RecordSignature;

    use HistoryTrait;
    use HasFactory;


    /**
     * fillable - attributes that can be mass-assigned.
     */
    protected $fillable = [
    [[foreach:columns]]
        '[[i.name]]',
    [[endforeach]]
    ];

    protected $hidden = [
        'created_by',
        'modified_by',
        'purged_by',
        'created_at',
        'updated_at',
    ];

    protected $fields = [
    [[foreach:columns]]
        "[[i.name]]" => [
            'label' => '[[i.display]]',
            'type' => 'text',
            'sequence' => 1,
            'name' => "[[i.name]]",
            'old' => '',
            'new' => '',
            'warning' => '',
            'isDirty' => false
        ],
        [[endforeach]]
    ];

    public function add($attributes)
    {
        try {
            $this->fill($attributes)->save();
        } catch (Exception $e) {
            info(__METHOD__ . ' line: ' . __LINE__ . ':  ' . $e->getMessage());
            throw new Exception($e->getMessage());
        } catch (QueryException $e) {
            info(__METHOD__ . ' line: ' . __LINE__ . ':  ' . $e->getMessage());
            throw new Exception($e->getMessage());
        }

        return true;
    }

    public function canDelete()
    {
        return true;
    }


    /**
     * Get Grid/index data PAGINATED.
     *
     * @param $per_page
     * @param $column
     * @param $direction
     * @param string $keyword
     * @return mixed
     */
    static function indexData(
        $per_page,
        $filters)
    {
        return self::buildBaseGridQuery($filters,
            ['[[tablename]].id',
                [[foreach:grid_columns]]
                    '[[tablename]].[[i.name]]',
                [[endforeach]]
            ])
            ->paginate($per_page);
    }


    /**
     * Create base query to be used by Grid, Download, and PDF.
     *
     * NOTE: to override the select you must supply all fields, ie you cannot add to the
     *       fields being selected.
     *
     * @param $column
     * @param $direction
     * @param string $keyword
     * @param string|array $columns
     * @return mixed
     */

    static function buildBaseGridQuery(
        $filters,
        $columns = '*')
    {
        // Validate sort direction
        $direction = strtolower($filters['sort_direction'] ?? "");
        $direction = in_array($direction, ['asc', 'desc']) ? $direction : 'asc';

        $query = self::select($columns)
            ->orderBy($filters['sort_column'], $direction);

        if ($keyword = $filters['keyword']) {
            $query->where('[[tablename]].[[name_field]]', 'like', '%' . $keyword . '%');
        }

        if (($active = data_get($filters, 'active',1)) != -1) {  // FILTER SETUP: set default
            $query->where('[[tablename]].active', $active);
        }


        return $query;
    }


    /**
     * Get export/Excel/download data query to send to Excel download library.
     *
     * @param $filters
     * @param string $columns
     * @return mixed
     */

    static function downloadDataQuery(
        $filters,
        $columns = '*')
    {
        return self::buildBaseGridQuery($filters, $columns);
    }

    /**
     * @param $filters
     * @param string $columns
     * @return mixed
     */
    static function pdfDataQuery(
        $filters,
        $columns = '*')
    {
        return self::buildBaseGridQuery($filters, $columns);
    }

    /**
     * Get definition of fields.
     *
     * @return string[][]
     */
    public function getFields()
    {
        return $this->fields;
    }


    /**
     * Get "options" for HTML select tag
     *
     * Return a collection of records.
     */
    static public function getOptions(): Collection
    {

        $thisModel = new static;

        $records = $thisModel::select('id',
            '[[tablename]].name')
            ->orderBy('[[tablename]].name')
            ->get();

        return $records;
    }

    /**
     * This adds any relation or other data to the history
     * @return array|null
     */
//    public function addHistoryData(): ?array
//    {
//        return [
//            'old' => [
//                'week_days' => static::$week_days_history
//            ],
//            'new' => [
//                'week_days' => $this->property_service_week_day()->get()->pluck('week_day_id')
//            ]
//        ];
//    }

    /**
     * @param History $history
     * @return History
     */
    static public function formattedHistoryComparison(History $history): History
    {

//        $callback = function ($compare) {
//            foreach ($compare as $field => $d) {
//                switch ($field) {
//                    case 'service_frequency_id':
//                        $compare['service_frequency_id']['new'] = $d['new']? ServiceFrequency::find($d['old'])->name ?? null : null;
//                        $compare['service_frequency_id']["old"] = $d['old']? ServiceFrequency::find($d['new'])->name ?? null : null;
//                        break;
//                    default:
//
//                }
//
//            }
//
//            return $compare;
//        };


        $diffHelper = (new DifferenceHelper($history['old'], $history['new']))
//            ->setCallback($callback)
            ->setFields((new self)->getFields())
            ->setShowOnlyDifferences(false);

        $history->setAttribute('diff', $diffHelper->get());

        return $history;
    }
}
