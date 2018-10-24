interface IVConfig {
  veranstaltungsID: number;
  bezeichnung: string;
  begin: Date;
  ende?: Date;
}

export interface IFieldConfig {
  label: string;
  name: string;
  rules?: Array<(val: boolean | string | number) => true | string>;
  required?: boolean;
  componentName?: string;
  counter?: number;
  disabeled?: boolean;
  [name: string]: any;
}

export interface IConfig {
  vConfig: IVConfig;
  form: Array<{
    name: string
    title: string
    hint?: string
    skipCheck?: (
      vConfig: IVConfig,
      bisher: Array<{ [key: string]: string | number | boolean }>
    ) => boolean
    errorCheck?: (
      vConfig: IVConfig,
      bisher: Array<{ [key: string]: string | number | boolean }>,
      current: { [key: string]: string | number | boolean }
    ) => true | string
    fields: IFieldConfig[]
  }>;
}
