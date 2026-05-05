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

export function getAverage(numbers:Array<number>) {
    return numbers.reduce((a, b) => a + b, 0) / numbers.length
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
            label: 'Accomplishment Report (1st Quarter)',
            value: 'accomplishment_report_1st_quarter',
        },
        {
            label: 'Accomplishment Report (2nd Quarter)',
            value: 'accomplishment_report_2nd_quarter',
        },
        {
            label: 'Accomplishment Report (3rd Quarter)',
            value: 'accomplishment_report_3rd_quarter',
        },
        {
            label: 'Accomplishment Report (4th Quarter)',
            value: 'accomplishment_report_4th_quarter',
        },
        {
            label: 'Monthly Attendance Report (1st Quarter)',
            value: 'monthly_attendance_report_1st_quarter',
        },
        {
            label: 'Monthly Attendance Report (2nd Quarter)',
            value: 'monthly_attendance_report_2nd_quarter',
        },
        {
            label: 'Monthly Attendance Report (3rd Quarter)',
            value: 'monthly_attendance_report_3rd_quarter',
        },
        {
            label: 'Monthly Attendance Report (4th Quarter)',
            value: 'monthly_attendance_report_4th_quarter',
        },
        {
            label: 'Activity Proposal (Major Activity)',
            value: 'activity_proposal_major_activity',
        },
         {
            label: 'Activity Proposal (Community-Based)',
            value: 'activity_proposal_community_based',
        },
        {
            label: 'Activity Report (Major Activity)',
            value: 'activity_report_major_activity',
        },
        {
            label: 'Activity Report (Community-Based)',
            value: 'activity_report_community_based',
        },
        {
            label: 'Attendance Summary Report (1st Semester)',
            value: 'attendance_summary_report_1st_semester',
        },
        {
            label: 'Attendance Summary Report (2nd Semester)',
            value: 'attendance_summary_report_2nd_semester',
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
            label: 'Accreditation Documents',
            value: 'accreditation_documents',
        },
        {
            label: 'Certificate of Completion',
            value: 'certificate_of_completion',
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
export function rating(value: any) {
    if(value === null || value === undefined || value === 0 || value === '') {
        return '-';
    }
    return value.toFixed(2);
}

export function getOrdinal(n: number) {
  const s = ["th", "st", "nd", "rd"];
  const v = n % 100;

  // Logic: Use (n-20)%10 or n%10, but fallback to "th" (index 0)
  // for anything that doesn't end in 1, 2, or 3, or is 11-13.
  return n + (s[(v - 20) % 10] || s[v] || s[0]);
}

export function reaccreditationLinks() {
    return [
        {"id": 7, "club": "Badminton Club", "link": "https://drive.google.com/drive/folders/1JevyLEHPFIi782wJOSAuhA-UALrK3rEF?usp=drive_link"},
        {"id": 5, "club": "Basketball Club", "link": "https://drive.google.com/drive/folders/15YP7qFlGqNfFhzQhuSwAMQb5cCrDECT-?usp=drive_link"},
        {"id": 3, "club": "Caraga Iskolar", "link": "https://drive.google.com/drive/folders/1ANjosisFdXSFG-OEYiQNgK31Zfmxv_8M?usp=drive_link"},
        {"id": 9, "club": "Debate Circle", "link": "https://drive.google.com/drive/folders/1hXz2XqgmtafcdKo3GEnvlRPXQBTPSrmU?usp=drive_link"},
        {"id": 17, "club": "Denominators Club", "link": "https://drive.google.com/drive/folders/1LWql50x-sPje8x38jGnCLRcz9MNOiBD0?usp=drive_link"},
        {"id": 12, "club": "Dibuho Club", "link": "https://drive.google.com/drive/folders/1vS7O6QykK18sZOu0_3VBv8kXJlzgLUPO?usp=drive_link"},
        {"id": 19, "club": "Frisbee Club", "link": "https://drive.google.com/drive/folders/1TjHfmFrS7Q99wwZJ01jUQW3DFexVbEBK?usp=drive_link"},
        {"id": 11, "club": "Homemakers Club", "link": "https://drive.google.com/drive/folders/17XllVpm534RATcQipzcqKu3PZkXzhJML?usp=drive_link"},
        {"id": 4, "club": "Model United Nations", "link": "https://drive.google.com/drive/folders/1LdfAgYDKzhC_4S9lQj0pGd--8ilADMK7?usp=drive_link"},
        {"id": 10, "club": "Multimedia Club", "link": "https://drive.google.com/drive/folders/1fLx2mQnjMb9Nup5xOAa6FFcyJLKiXJSV?usp=drive_link"},
        {"id": 15, "club": "Music Club", "link": "https://drive.google.com/drive/folders/1F1rcA2qO3uu9UcH7UAbK55uBe3agVp6C?usp=drive_link"},
        {"id": 16, "club": "Red Cross Youth Club", "link": "https://drive.google.com/drive/folders/1TsXRvJNWFQUBXm1plMUMNs_rGRaS9KV-?usp=drive_link"},
        {"id": 13, "club": "Robotics Enthusiasts", "link": "https://drive.google.com/drive/folders/1RHve-nuBfVXboORqLwHm0PlbEY8fJ7xs?usp=drive_link"},
        {"id": 14, "club": "Sigalab Dancetroupe", "link": "https://drive.google.com/drive/folders/1eLt0W-_VwUYzeS-l0HVtmhWFukR_2vUj?usp=drive_link"},
        {"id": 2, "club": "The Thirteenth Scholars", "link": "https://drive.google.com/drive/folders/1gJPv17fMHJnDHSi0Wi9rQoyaTd6I8JbP?usp=drive_link"},
        {"id": 6, "club": "Volleyball Club", "link": "https://drive.google.com/drive/folders/1gpwErH-ZsIImXJE1lwpNCPwT4Xq-JWqp?usp=drive_link"}
    ]
}

export function decapitalizeText(name: string) {
  if (!name) return "";

  return name
    .toLowerCase()
    .split(' ')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ');
}
