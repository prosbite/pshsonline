import { computed, ref } from 'vue'

export type LearnerSortField = 'name' | 'grade_section' | 'gender'
export type SortDirection = 'asc' | 'desc'

type LearnerLike = {
    last_name?: string
    first_name?: string
    gender?: string
    current_enrollment?: {
        section?: {
            grade_level_id?: number | string
            section_name?: string
        }
    }
    learner?: LearnerLike
}

const getLearner = (item: LearnerLike) => item?.learner ?? item

const compareNames = (a: LearnerLike, b: LearnerLike) => {
    const learnerA = getLearner(a)
    const learnerB = getLearner(b)

    const lastNameCompare = (learnerA.last_name ?? '').localeCompare(learnerB.last_name ?? '')
    if (lastNameCompare !== 0) {
        return lastNameCompare
    }

    return (learnerA.first_name ?? '').localeCompare(learnerB.first_name ?? '')
}

const compareGender = (a: LearnerLike, b: LearnerLike) => {
    const learnerA = getLearner(a)
    const learnerB = getLearner(b)

    const genderCompare = (learnerA.gender ?? '').localeCompare(learnerB.gender ?? '')
    if (genderCompare !== 0) {
        return genderCompare
    }

    return compareNames(a, b)
}

const compareGradeSection = (a: LearnerLike, b: LearnerLike) => {
    const learnerA = getLearner(a)
    const learnerB = getLearner(b)

    const aGrade = Number(learnerA.current_enrollment?.section?.grade_level_id ?? 0)
    const bGrade = Number(learnerB.current_enrollment?.section?.grade_level_id ?? 0)
    if (aGrade !== bGrade) {
        return aGrade - bGrade
    }

    const sectionCompare = (learnerA.current_enrollment?.section?.section_name ?? '').localeCompare(
        learnerB.current_enrollment?.section?.section_name ?? '',
    )
    if (sectionCompare !== 0) {
        return sectionCompare
    }

    return compareNames(a, b)
}

const sorters = {
    name: compareNames,
    gender: compareGender,
    grade_section: compareGradeSection,
}

export function sortLearners<T extends LearnerLike>(
    items: T[] = [],
    field: LearnerSortField = 'name',
    direction: SortDirection = 'asc',
) {
    return [...items].sort((a, b) => {
        const result = sorters[field](a, b)
        return direction === 'asc' ? result : result * -1
    })
}

export function useLearnerSorting(initialField: LearnerSortField = 'name') {
    const sortField = ref<LearnerSortField>(initialField)
    const sortDirection = ref<SortDirection>('asc')

    const setSort = (field: LearnerSortField) => {
        if (sortField.value === field) {
            sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
            return
        }

        sortField.value = field
        sortDirection.value = 'asc'
    }

    const sortItems = <T extends LearnerLike>(items: T[] = []) => {
        return sortLearners(items, sortField.value, sortDirection.value)
    }

    return {
        sortField,
        sortDirection,
        setSort,
        sortItems,
        sortedFieldLabel: computed(() => sortField.value),
    }
}
