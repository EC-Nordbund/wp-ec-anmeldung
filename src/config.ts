export interface Event {
  id: number;
  title: string;
  start: Date;
  end?: Date;
}

export interface Step {
  name: string;
  title: string;
  hint?: string;
  conditions?: Conditions;
  fields: Field[];
}

export interface Field {
  name: string;
  label: string;
  rules?: Array<(val: boolean | string | number) => boolean | string>;
  required?: boolean;
  component?: string;
  lenght?: number;
  disabeled?: boolean;
  [key: string]: any;
}

export interface Conditions {
  skip?: (
    event: Event,
    previous: Array<{ [key: string]: string | number | boolean }>,
  ) => boolean;
  error?: (
    event: Event,
    previous: Array<{ [key: string]: string | number | boolean }>,
    current: { [key: string]: string | number | boolean },
  ) => boolean | string;
}

export interface Form {
  start?: Date;
  end?: Date;
  steps: Step[];
}
