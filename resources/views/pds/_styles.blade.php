<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Arial', sans-serif; font-size: 10px; background: #fff; padding: 10px; }
    .pds-container { max-width: 8.5in; margin: 0 auto; background: #fff; }

    .pds-header { border-bottom: 2px solid #000; padding-bottom: 6px; margin-bottom: 10px; }
    .pds-header .form-no { font-size: 9px; font-weight: bold; font-style: italic; }
    .pds-title { font-size: 20px; font-weight: bold; text-transform: uppercase; text-align: center; margin: 4px 0; }
    .pds-warning { font-size: 8px; font-weight: bold; font-style: italic; margin-top: 4px; }
    .pds-instructions { font-size: 8px; font-style: italic; margin-top: 3px; }

    .pds-section { margin-bottom: 10px; border: 1px solid #000; padding: 8px; page-break-inside: avoid; }
    .pds-section-title { background: #999; color: #fff; font-weight: bold; padding: 3px 8px; margin: -8px -8px 8px -8px; border-bottom: 1px solid #000; font-size: 11px; }

    .pds-field { display: flex; margin-bottom: 3px; align-items: baseline; font-size: 9px; }
    .pds-label { width: 150px; font-weight: bold; flex-shrink: 0; font-size: 9px; }
    .pds-label-sm { width: 110px; font-weight: normal; font-style: italic; flex-shrink: 0; }
    .pds-value { border-bottom: 1px solid #000; padding: 0 5px; flex: 1; min-height: 18px; font-size: 9px; }
    .pds-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 5px; }
    .pds-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 5px; }

    .pds-checkgroup { font-size: 9px; margin-bottom: 3px; }
    .pds-check { display: inline-block; width: 10px; height: 10px; border: 1px solid #000; margin-right: 3px; text-align: center; vertical-align: middle; line-height: 10px; font-size: 8px; }
    .pds-check.checked::after { content: '✓'; font-weight: bold; }

    .pds-table { width: 100%; border-collapse: collapse; margin-top: 3px; font-size: 8px; }
    .pds-table th, .pds-table td { border: 1px solid #000; padding: 3px 4px; text-align: left; font-size: 8px; vertical-align: top; }
    .pds-table th { background: #e8e8e8; font-weight: bold; text-align: center; vertical-align: middle; }
    .pds-table td.blank { height: 16px; }

    /* Items 34-40: question on the left, response on the right */
    .pds-qtable td { font-size: 9px; padding: 4px 6px; }
    .pds-qtable td.q { width: 66%; }
    .pds-qtable td.r { width: 34%; }
    .pds-qtable .det { margin-top: 3px; font-size: 8px; }
    .pds-qtable .fill { display: block; border-bottom: 1px solid #000; min-height: 12px; font-weight: bold; }
    .pds-qtable .sub { margin-left: 12px; }

    .pds-note { font-size: 8px; font-style: italic; margin-bottom: 4px; }
    .pds-photo-box, .pds-thumbmark-box { border: 1px solid #000; display: flex; align-items: center; justify-content: center; text-align: center; font-size: 7px; padding: 4px; }
    .pds-photo-box { width: 90px; height: 115px; flex-shrink: 0; }
    .pds-thumbmark-box { width: 90px; height: 90px; }
    .pds-sign-box { border: 1px solid #000; min-height: 40px; padding: 4px; font-size: 9px; text-align: center; color: #c00; font-style: italic; }

    .pds-print-btn { text-align: center; margin: 20px; padding: 20px; }
    .pds-print-btn button { padding: 10px 30px; font-size: 14px; cursor: pointer; background: #0d6efd; color: #fff; border: none; border-radius: 5px; margin: 0 5px; }
    .pds-print-btn button:hover { background: #0b5ed7; }
    .pds-print-btn button.btn-secondary { background: #6c757d; }
    .pds-print-btn button.btn-secondary:hover { background: #5c636a; }
    .pds-print-btn button.btn-success { background: #198754; }
    .pds-print-btn button.btn-success:hover { background: #157347; }
    .page-break { page-break-after: always; }

    @media print {
        body { padding: 0; margin: 0; background: #fff; }
        .pds-container { padding: 10px; max-width: 100%; }
        .no-print { display: none !important; }
        .pds-section { page-break-inside: avoid; }
    }
</style>