<div class="pds-print-btn no-print">
    <button onclick="window.print()">🖨️ Print PDS Form</button>
    <button onclick="window.location.href='{{ route('pds.pdf', $user->id) }}'" class="btn-success">📄 Download PDF</button>
    <button onclick="window.close()" class="btn-secondary">✖ Close</button>
</div>

<script>
    if (window.location.search.includes('print=true')) {
        window.onload = function () { window.print(); }
    }
</script>