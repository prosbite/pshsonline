import { ref } from 'vue'

export function sortAttendanceData(advisers: any[], attendances: any[]) {
 let finalData = []
    advisers.forEach((adviser: any) => {
        let adviserData = {
            adviser: adviser,
            attendances: {},
            totalQ: 0,
            totalE: 0,
            totalT: 0,
        }
        for (let i in attendances) {
            let count = 0
            attendances[i].forEach((a: any, j: number) => {
                if (a.adviser === adviser) {
                    adviserData.attendances[i] = a
                    adviserData.totalQ += a.q
                    adviserData.totalE += a.e
                    adviserData.totalT += a.t
                    count++
                }
            });
            if(count === 0){
                adviserData.attendances[i] = {
                    adviser: adviser,
                    club: null,
                    q: 0,
                    e: 0,
                    t: 0,
                }
                adviserData.totalQ += 0
                adviserData.totalE += 0
                adviserData.totalT += 0
            }
        }
        finalData.push(adviserData)
    });
    return finalData
}
