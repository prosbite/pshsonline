export function useConstants() {
    const genders = ['male', 'female']
    return {
        genders
    }
}

export function ucWords(str: string) {
    if (str === '' || !str) {
        return '';
    }
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

export function formatDate(isoString: any) {
    if(!isoString) {
        return '';
    }
    const date = new Date(isoString);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0"); // keep 2 digits
    const day = date.getDate(); // single digit allowed
    return `${year}-${month}-${day}`;
}

export function fullDateTime(date: string) {
    return new Date(date).toLocaleString('en-locale', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true,
    });
}

export function justTime(dateInput: string) {
    const date = (dateInput instanceof Date) ? dateInput : new Date(dateInput);

    let hours = date.getHours();
    let minutes = date.getMinutes();
    const ampm = hours >= 12 ? 'PM' : 'AM';

    hours = hours % 12;
    hours = hours ? hours : 12; // 0 → 12
    minutes = minutes < 10 ? '0' + minutes : minutes;

    return `${hours}:${minutes} ${ampm}`;
}

export function shortDate(dateStr: string) {
    const date = new Date(dateStr)
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
}

export function formatMonth(ym: string) {
    const [year, month] = ym.split('-');
    // Month is 1-based in string, but 0-based in Date
    const date = new Date(year, month - 1);
    return date.toLocaleString('en-US', { month: 'long' });
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
    if (str === '' || !str) {
        return '';
    }
    return str.replace(/_/g, ' ');
}

export function clubTypes() {
    return [
        {
            label: 'Club',
            value: 'club',
        },
        {
            label: 'School Organization',
            value: 'school_organization',
        }
    ]
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

export function advisersAttendanceStatus() {
    return [
        {
            label: 'Present',
            value: 'present',
            color: 'text-indigo-500',
            abv: 'P'
        },
        {
            label: 'Absent',
            value: 'absent',
            color: 'text-red-500',
            abv: 'A'
        },
        {
            label: 'Tardy',
            value: 'tardy',
            color: 'text-yellow-500',
            abv: 'T'
        },

    ]
}

export function submissionType() {
    return [
        {
            label: 'Accomplishment Report (Quarterly)',
            value: 'accomplishment_report',
        },
        {
            label: 'Accreditation Documents',
            value: 'accreditation_documents',
        },
        {
            label: 'Activity Proposal',
            value: 'activity_proposal',
        },
        {
            label: 'Activity Report',
            value: 'activity_report',
        },
        {
            label: 'Attendance Summary Report (Semestral)',
            value: 'attendance_summary_report',
        },
        {
            label: 'Monthly Attendance Report (Quarterly)',
            value: 'monthly_attendance_report',
        },
        {
            label: 'Reaccreditation Documents',
            value: 'reaccreditation_documents',
        },
        {
            label: 'Substitution Plan',
            value: 'substitution_plan',
        },
        {
            label: 'Others',
            value: 'others',
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
            label: 'For Revision',
            value: 'for_revision',
        },
        {
            label: 'For Printing',
            value: 'for_printing',
        },
        {
            label: 'Completed',
            value: 'completed',
        },
    ]
}

export function replicate(object: any) {
    return JSON.parse(JSON.stringify(object));
}

export function formatDateUTC(isoString: any) {
    return new Date(isoString).toISOString().split("T")[0];
}

export function formatDateLocal(isoString: any) {
    const date = new Date(isoString);
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, "0");
    const d = String(date.getDate()).padStart(2, "0");
    return `${y}-${m}-${d}`;
}
