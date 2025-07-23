export function useConstants() {
    const genders = ['male', 'female']
    return {
        genders
    }
}

export function ucWords(str: string) {
    return str.replace(/\b\w/g, (match) => match.toUpperCase());
}

export function middleInitials(str: string) {
    if (str === '' || !str) {
        return '';
    }
    return str.replace(/\b\w/g, (match) => match.toUpperCase()).slice(0, 1) + '.';
}

export function fullDate(date: string) {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
}

export function exportToCSV(data: any[], filename: string = 'export.csv') {
      if (!data || !data.length) {
        console.warn('No data provided for CSV export.');
        return;
      }

      const csvRows = [];
      const headers = Object.keys(data[0]);
      csvRows.push(headers.join(','));

      data.forEach(row => {
        const values = headers.map(header => {
          const val = row[header] ?? '';
          return `"${String(val).replace(/"/g, '""')}"`;
        });
        csvRows.push(values.join(','));
      });

      const blob = new Blob([csvRows.join('\n')], { type: 'text/csv' });
      const url = URL.createObjectURL(blob);

      const a = document.createElement('a');
      a.href = url;
      a.download = filename;
      a.click();

      URL.revokeObjectURL(url);
}
