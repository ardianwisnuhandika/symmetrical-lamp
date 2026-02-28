/**
 * Data Export Utility
 * 
 * Provides functions to export data to CSV, Excel, and PDF formats.
 * Supports exporting admin lists and custom data with configurable columns.
 */

const ADMIN_COLUMNS = [
  { key: 'nama', label: 'Nama Lengkap' },
  { key: 'username', label: 'Username' },
  { key: 'role', label: 'Role/Akses' },
  { key: 'email', label: 'Email' },
  { key: 'no_hp', label: 'Nomor HP' },
  { key: 'status', label: 'Status Akun' },
  { key: 'last_login', label: 'Login Terakhir', transform: (v) => v ? new Date(v).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }) : 'Belum pernah' }
];

const generateStyledTable = (data, columns) => {
  const headers = columns.map(col => `<th>${col.label}</th>`).join('');
  
  const rows = data.map(item => {
    return `<tr>${columns.map(col => {
      let value = item[col.key];
      if (col.transform) value = col.transform(value);
      if (value === null || value === undefined || value === '') value = '-';
      if (typeof value === 'object') value = JSON.stringify(value);
      return `<td>${value}</td>`;
    }).join('')}</tr>`;
  }).join('');

  return `
    <table class="export-table">
      <thead>
        <tr>${headers}</tr>
      </thead>
      <tbody>
        ${rows}
      </tbody>
    </table>
  `;
};

/**
 * Export data to CSV format
 * @param {Array} data - Array of data objects to export
 * @param {string} filename - Base filename (without extension)
 * @param {Array} columns - Column definitions with key, label, and optional transform function
 */
export const exportToCSV = (data, filename, columns) => {
  const BOM = '\uFEFF';
  const headers = columns.map(col => col.label);
  
  const rows = data.map(item => {
    return columns.map(col => {
      let value = item[col.key];
      if (col.transform) value = col.transform(value);
      if (value === null || value === undefined || value === '') value = '-';
      if (typeof value === 'object') value = JSON.stringify(value);
      value = String(value);
      if (value.includes(',') || value.includes('"') || value.includes('\n') || value.includes(';')) {
        return `"${value.replace(/"/g, '""')}"`;
      }
      return value;
    });
  });

  const csvContent = [
    headers.join(';'),
    ...rows.map(row => row.join(';'))
  ].join('\n');

  const blob = new Blob([BOM + csvContent], { type: 'text/csv;charset=utf-8;' });
  const link = document.createElement('a');
  link.href = URL.createObjectURL(blob);
  link.download = `${filename}_${new Date().toISOString().split('T')[0]}.csv`;
  link.click();
  URL.revokeObjectURL(link.href);
};

/**
 * Export data to Excel format
 * @param {Array} data - Array of data objects to export
 * @param {string} filename - Base filename (without extension)
 * @param {Array} columns - Column definitions with key, label, and optional transform function
 */
export const exportToExcel = (data, filename, columns) => {
  const tableHTML = generateStyledTable(data, columns);
  
  const excelTemplate = `
    <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
      <head>
        <meta charset="UTF-8">
        <style>
          body { margin: 0; padding: 18px; font-family: Calibri, Arial, sans-serif; color: #0f172a; }
          .header { margin-bottom: 14px; border-bottom: 2px solid #0284c7; padding-bottom: 10px; }
          .title { font-size: 18px; font-weight: 700; color: #075985; margin: 0 0 4px 0; }
          .meta { font-size: 11px; color: #475569; margin: 0; }
          .export-table { border-collapse: collapse; width: 100%; table-layout: auto; }
          .export-table th {
            background: linear-gradient(180deg, #0ea5e9 0%, #0284c7 100%);
            color: #fff;
            font-weight: 700;
            padding: 8px 7px;
            text-align: left;
            border: 1px solid #0c4a6e;
            font-size: 10px;
            white-space: nowrap;
          }
          .export-table td {
            padding: 7px;
            border: 1px solid #cbd5e1;
            font-size: 10px;
            vertical-align: top;
            white-space: normal;
            word-break: normal;
            overflow-wrap: break-word;
          }
          .export-table tr:nth-child(even) { background-color: #f8fafc; }
        </style>
      </head>
      <body>
        <div class="header">
          <p class="title">${filename.replace(/_/g, ' ')}</p>
          <p class="meta">Diekspor: ${new Date().toLocaleString('id-ID')}</p>
        </div>
        ${tableHTML}
      </body>
    </html>
  `;
  
  const blob = new Blob([excelTemplate], { type: 'application/vnd.ms-excel;charset=utf-8' });
  const link = document.createElement('a');
  link.href = URL.createObjectURL(blob);
  link.download = `${filename}_${new Date().toISOString().split('T')[0]}.xls`;
  link.click();
  URL.revokeObjectURL(link.href);
};

/**
 * Export data to PDF format (opens print dialog)
 * @param {Array} data - Array of data objects to export
 * @param {string} filename - Base filename (for display)
 * @param {Array} columns - Column definitions with key, label, and optional transform function
 */
export const exportToPDF = (data, filename, columns) => {
  const title = filename.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
  const tableHTML = generateStyledTable(data, columns);
  
  const printWindow = window.open('', '_blank');
  printWindow.document.write(`
    <!DOCTYPE html>
    <html>
      <head>
        <meta charset="UTF-8">
        <title>${title}</title>
        <style>
          * { margin: 0; padding: 0; box-sizing: border-box; }
          @page { size: A4 landscape; margin: 10mm; }
          body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 12px; color: #1e293b; font-size: 9px; }
          .header { display: flex; align-items: center; gap: 12px; margin: 0 auto 14px auto; padding-bottom: 10px; border-bottom: 2px solid #0ea5e9; width: 100%; }
          .header-info h1 { font-size: 16px; color: #0c4a6e; margin-bottom: 3px; }
          .header-info p { font-size: 10px; color: #64748b; }
          .export-table { width: 100%; border-collapse: collapse; table-layout: auto; font-size: 8px; }
          .export-table th { background-color: #0284c7; color: white; padding: 6px 4px; text-align: left; font-size: 8px; text-transform: uppercase; white-space: nowrap; border: 1px solid #075985; }
          .export-table td { padding: 5px 4px; border: 1px solid #cbd5e1; font-size: 8px; white-space: normal; word-break: normal; overflow-wrap: break-word; vertical-align: top; }
          .export-table tr:nth-child(even) { background-color: #f8fafc; }
          .footer { margin-top: 10px; text-align: right; font-size: 8px; color: #94a3b8; }
          @media print { 
            body { padding: 0; } 
            th { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            tr { page-break-inside: avoid; }
          }
        </style>
      </head>
      <body>
        <div class="header">
          <div class="header-info">
            <h1>${title}</h1>
            <p>Dicetak pada: ${new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</p>
          </div>
        </div>
        ${tableHTML}
        <div class="footer">Generated by Admin System</div>
      </body>
    </html>
  `);
  printWindow.document.close();
  printWindow.print();
};

/**
 * Export admin list to specified format
 * @param {Array} admins - Array of admin objects
 * @param {string} format - Export format: 'csv', 'excel', or 'pdf'
 */
export const exportAdmins = (admins, format) => {
  const filename = 'Data_Administrator';
  
  if (format === 'csv') {
    exportToCSV(admins, filename, ADMIN_COLUMNS);
  } else if (format === 'excel') {
    exportToExcel(admins, filename, ADMIN_COLUMNS);
  } else if (format === 'pdf') {
    exportToPDF(admins, filename, ADMIN_COLUMNS);
  }
};
