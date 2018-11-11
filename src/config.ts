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
  skip_ü18?: boolean;
  rules?: Array<(stepperVal:any)=>boolean>
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

export interface Form {
  start?: Date;
  end?: Date;
  steps: Step[];
}
