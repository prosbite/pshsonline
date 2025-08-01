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

export function fullDateTime(date: string) {
    return new Date(date).toLocaleString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true,
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

export function removeUnderScore(str: string) {
    return str.replace(/_/g, ' ');
}

export function attendanceStatus() {
    return [
        {
            label: 'Present',
            value: 'present',
            color: 'text-indigo-500',
            abv: 'P'
        },
        {
            label: 'Excused Absence',
            value: 'excused_absence',
            color: 'text-green-500',
            abv: 'EA'
        },
        {
            label: 'Unexcused Absence',
            value: 'unexcused_absence',
            color: 'text-red-500',
            abv: 'UA'
        },
        {
            label: 'Tardy',
            value: 'tardy',
            color: 'text-yellow-500',
            abv: 'T'
        },
        {
            label: 'Cutting Classes',
            value: 'cutting_classes',
            color: 'text-gray-500',
            abv: 'CC'
        }
    ]
}

export function submissionType() {
    return [
        {
            label: 'Accreditation Documents (For Review)',
            value: 'accreditation_documents_review',
        },
        {
            label: 'Accreditation Documents (Printed)',
            value: 'accreditation_documents_printed',
        },
        {
            label: 'Reaccreditation Documents (For Review)',
            value: 'reaccreditation_documents_review',
        },
        {
            label: 'Reaccreditation Documents (Printed)',
            value: 'reaccreditation_documents_printed',
        },
        {
            label: 'Activity Proposal (For Review)',
            value: 'activity_proposal_review',
        },
        {
            label: 'Activity Proposal (Printed)',
            value: 'activity_proposal_printed',
        },
        {
            label: 'Activity Report (For Review)',
            value: 'activity_report_review',
        },
        {
            label: 'Activity Report (Printed)',
            value: 'activity_report_printed',
        },
        {
            label: 'Attendance Report',
            value: 'attendance_report',
        },
        {
            label: 'Summary of Attendance',
            value: 'summary_of_attendance',
        },
    ]
}

export function submissionProgression() {
    return [
        {
            label: 'Pending',
            value: 'pending',
        },
        {
            label: 'Ongoing Review',
            value: 'ongoing_review',
        },
        {
            label: 'Completed (For Revision)',
            value: 'completed_for_revision',
        },
        {
            label: 'Completed (For Printing)',
            value: 'completed_for_printing',
        },
    ]
}
