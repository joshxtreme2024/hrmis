{{-- The PDS must carry the e-signature and date of accomplishment at the bottom of every page (CSC guide) --}}
<div class="pds-section" style="border-top: 2px solid #000;">
    <div class="pds-grid">
        <div>
            <div style="font-weight:bold; font-size:9px; margin-bottom:3px;">SIGNATURE</div>
            <div class="pds-sign-box">{{ $p->signature ?? '(wet signature/e-signature/digital certificate)' }}</div>
        </div>
        <div>
            <div style="font-weight:bold; font-size:9px; margin-bottom:3px;">DATE</div>
            <div class="pds-sign-box" style="color:#000; font-style:normal;">{{ $p->signature_date ? date('d/m/Y', strtotime($p->signature_date)) : '' }}</div>
        </div>
    </div>
    <div style="text-align:right; font-size:7px; margin-top:4px;">CS FORM 212 (Revised 2026), Page {{ $pageNo }} of 4</div>
</div>