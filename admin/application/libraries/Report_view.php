<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Shows a report "spec" as a page (views/report_table.php) or downloads it as CSV (?export=csv).
 *
 * spec: title, menu (active menu name), route, hub (back link), year, filters[], selects[],
 *       columns[{key,label,type,total}], rows[], group (bool, rows carry _group), note, need, count_label
 * data: f (filter values), classes, div_map, fees, modes
 */
class Report_view
{
    public function output($spec, $data)
    {
        $CI =& get_instance();
        $spec += array('filters' => array(), 'selects' => array(), 'group' => false, 'note' => '', 'need' => '',
                       'count_label' => 'rows', 'year' => '', 'hub' => '');
        $data += array('f' => array(), 'classes' => array(), 'div_map' => array(), 'fees' => array(), 'modes' => array());

        if ($CI->input->get('export') === 'csv') {
            $this->csv($spec);
            exit;
        }
        $data['r'] = $spec;
        $CI->load->view('header', array('pageTitle' => $spec['title'], 'activePage' => $spec['menu'],
                                        'pageStylesheets' => array(base_url('assets/css/fee.css'))));
        $CI->load->view('report_table', $data);
        $CI->load->view('footer');
    }

    public function csv($spec)
    {
        $name = preg_replace('/[^a-z0-9]+/i', '_', strtolower($spec['title'])) . '_' . date('Ymd') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $name . '"');
        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF");   // UTF-8 BOM for Excel

        $head = array();
        if ($spec['group']) $head[] = 'Group';
        foreach ($spec['columns'] as $c) $head[] = $c['label'];
        fputcsv($out, $head);

        $tot = array();
        foreach ($spec['rows'] as $row) {
            $line = array();
            if ($spec['group']) $line[] = $row['_group'];
            foreach ($spec['columns'] as $c) {
                $v = isset($row[$c['key']]) ? $row[$c['key']] : '';
                if (isset($c['type']) && $c['type'] === 'money') $v = $v === '' ? '' : number_format((float)$v, 2, '.', '');
                $line[] = $v;
                if (!empty($c['total'])) {
                    $tot[$c['key']] = (isset($tot[$c['key']]) ? $tot[$c['key']] : 0) + (float)(isset($row[$c['key']]) ? $row[$c['key']] : 0);
                }
            }
            fputcsv($out, $line);
        }
        if ($tot) {
            $line = $spec['group'] ? array('') : array();
            foreach ($spec['columns'] as $i => $c) {
                $line[] = isset($tot[$c['key']]) ? number_format($tot[$c['key']], 2, '.', '') : ($i === 0 ? 'TOTAL' : '');
            }
            fputcsv($out, $line);
        }
        fclose($out);
    }
}
